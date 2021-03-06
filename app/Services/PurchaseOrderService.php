<?php

namespace App\Services;

use App\Models\PurchaseOrder as PurchaseOrder;
use App\Repositories\PurchaseOrderDetailsRepository;
use App\Repositories\PurchaseOrderRepository;
use App\Services\CommonService as CommonService;
use App\Services\TicketService as TicketService;
use App\Services\PricingService as PricingService;
use App\Http\Helper;
use App\Repositories\ShowRepository;
use App\Services\ShowService;
class PurchaseOrderService
{
    private $purchaseOrderModel;
    private $commonService;
    private $ticketService;
    private $priceService;
    private $purchaseOrderDetailRepository;
    private $purchaseOrderRepo;
    private $showRepo;
    protected $showService;

    public function __construct(
        PurchaseOrderDetailsRepository $purchaseOrderDetailRepository,
        PurchaseOrder $purchaseOrderModel,
        CommonService $commonService,
        TicketService $ticketService,
        PricingService $pricingService,
        PurchaseOrderRepository $purchaseOrderRepo,
        ShowRepository $showRepo,
        ShowService $showService
    ) {
        $this->purchaseOrderModel = $purchaseOrderModel;
        $this->commonService = $commonService;
        $this->ticketService = $ticketService;
        $this->pricingService = $pricingService;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->purchaseOrderRepo = $purchaseOrderRepo;
        $this->showRepo = $showRepo;
        $this->showService = $showService;
    }

    public function add($request)
    {

        $request->validate([
            'show_id' => 'required',
            'num_of_tickets' => 'required|integer|min:1',
            'selected_seats' => 'required',
            'movie_id' => 'required',
            'screen_id' => 'required',
        ]);
        
        $params = $request->all();
        $isSeatsAvailable = $this->checkSeatsAvailableForBooking($params['selected_seats'], $params['show_id']);
        if (!$isSeatsAvailable) {
            return Helper::prettyApiResponse(trans('messages.seats_unavailable'), 'error');
        }

        $pricePackageId = $this->showRepo->fetchPricePackageId($params['show_id']);
        if (!$pricePackageId) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'errors' => trans('messages.missing_show_price_mapping'),
            ]);
        }

        $priceData = $this->pricingService->calculateTicketCharge($pricePackageId,count($params['selected_seats']));
        $purcaseOrder = [
            'num_of_tickets' => count($params['selected_seats']),
            'amount' => (float)$priceData['total_amount'],
            'show_id' => (int)$params['show_id'],
            'screen_id' => (int)$params['screen_id'],
            'movie_id' => (int)$params['movie_id'],
        ];

        $purchaseOrderId = $this->purchaseOrderModel->store($purcaseOrder);

        if (!$purchaseOrderId) {
            return Helper::prettyApiResponse(trans('messages.insert_failure', ['item' => 'Purchase']));
        }
        $this->ticketService->saveTickets($params['selected_seats'], $params['movie_id'], $params['screen_id'], $params['show_id'], $purchaseOrderId);
        $this->savePurchaseOrderDetails($purchaseOrderId, $priceData['pricing'], $params['selected_seats']);
        $this->showService->updateShowStatistics($params['show_id']);
        return Helper::prettyApiResponse(trans('messages.insert_success', ['item' => 'Ticket']));
    }

    public function checkSeatsAvailableForBooking($selectedSeats, $showId)
    {

        $bookedSeats = $this->ticketService->fetechTicketsBooked($showId);
        if (array_intersect($selectedSeats, $bookedSeats)) {
            return false;
        }
        return true;
    }

    public function savePurchaseOrderDetails($purchaseOrderId, $priceData, $noOfSeats)
    {

        $data = [];
        foreach ($priceData as $price) {
            $data[] = [
                'num_of_items' => (int)$noOfSeats,
                'amount' => $price['amount'],
                'purchase_order_id' => $purchaseOrderId,
                'pricing_id' => $price['id'],
            ];
        }
        return $this->purchaseOrderDetailRepository->save($data);
    }

    public function getPurchaseHistory($request){

        $request->validate([
            'showId'=>'required',
        ]);
        return $this->purchaseOrderRepo->getPurchaseHistory($request->showId);
    }

}
