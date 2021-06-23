<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\PricingService as PricingService;
use App\Services\CommonService as CommonService;
use App\Http\Helper;

class PricingController extends Controller
{
    public $pricingService;
    public $commonService;
    public function __construct(PricingService $pricingService, CommonService $commonService)
    {
        $this->pricingService = $pricingService;
        $this->commonService = $commonService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Helper::prettyApiResponse('', 'success', $this->pricingService->list());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $requiredFields = [
            'name' => 'required',
            'value' => 'required|integer',
            'is_value_in_percentage' => 'required',
        ];

        $validator = Validator::make($params, $requiredFields);
        if ($validator->fails()) {
            Helper::prettyApiResponse($this->commonService->getErrorMessagesFromValidator($validator->errors()), 'error');
        }
        $this->pricingService->store($params);
        return Helper::prettyApiResponse(trans('messages.insert_success', ['item' => 'Pricing']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTicketCharge(Request $request, $num)
    {

        if (!$num) {
            return Helper::prettyApiResponse(trans('messages.invalid_ticket_charge_input'), 'error');
        }

        return Helper::prettyApiResponse(trans('messages.ticket_charge_calculation_success'),'success',$this->pricingService->calculateTicketCharge($num));
    }
}
