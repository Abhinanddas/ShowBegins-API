<?php

namespace App\Services;

use App\Models\Pricing as Pricing;
use App\Services\CommonService as CommonService;
use DateTime;
use Illuminate\Support\Facades\Validator;
use App\Repositories\PriceRepository;
use App\Repositories\ShowRepository;

class PricingService
{
    private $priceModel;
    private $commonService;
    protected $priceRepo;
    protected $showRepo;

    public function __construct(Pricing $pricing, CommonService $commonService, PriceRepository $priceRepo, ShowRepository $showRepo)
    {
        $this->priceModel = $pricing;
        $this->commonService = $commonService;
        $this->priceRepo = $priceRepo;
        $this->showRepo = $showRepo;
    }

    public function store($params)
    {
        $data['name'] = $params['name'];
        $data['value'] = (int)$params['value'];
        $data['is_value_in_percentage'] = $params['is_value_in_percentage'] == 'true' ? true : false;
        return $this->priceModel->store($data);
    }

    public function list()
    {
        return $this->priceModel->getAllPricing();
    }

    public function getTicketCharge($request)
    {

        $request->validate([
            'showId' => 'required',
            'num' => 'required|integer'
        ]);

        $showIdExist = $this->commonService->checkIfDataExists($request->showId, 'shows');

        if (!$showIdExist) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'errors' => trans('messages.entity_not_found', ['entity' => 'Show Id']),
            ]);
        }

        $pricePackageId = $this->showRepo->fetchPricePackageId($request->showId);

        if (!$pricePackageId) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'errors' => trans('messages.missing_show_price_mapping'),
            ]);
        }

        return $this->calculateTicketCharge($pricePackageId, $request->num);
    }

    public function calculateTicketCharge($pricePackageId, $num)
    {
        $pricePackage = $this->priceRepo->getPricePackageDetails($pricePackageId);
        $baseCharge = 0;
        $totalCharge = 0;
        $priceArray = [];

        foreach ($pricePackage['base_charges'] as $price) {
            $baseCharge += $price->value;
            $priceArray[] = ['id' => $price->id, 'name' => $price->name, 'amount' => $baseCharge];
            $totalCharge += $price->value * $num;
        }

        foreach ($pricePackage['other_charges'] as $charge) {
            if ($charge->is_percentage) {
                $amount  = $this->commonService->calculatePercentage($baseCharge, $charge->value) * $num;
            } else {
                $amount = $charge->value * $num;
            }
            $priceArray[] = ['id' => $charge->id, 'name' => $charge->name, 'amount' => $amount];
            $totalCharge += $amount;
        }
        return ['total_amount' => $totalCharge, 'pricing' => $priceArray];
    }

    public function checkForPercentageType($priceIds)
    {
        return  $this->priceRepo->checkForPercentageType($priceIds) ? true : false;
    }
}
