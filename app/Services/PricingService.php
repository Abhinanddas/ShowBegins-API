<?php

namespace App\Services;

use App\Models\Pricing as Pricing;
use App\Services\CommonService as CommonService;
use DateTime;
use Illuminate\Support\Facades\Validator;

class PricingService
{
    private $priceModel;
    private $commonService;

    public function __construct(Pricing $pricing, CommonService $commonService)
    {
        $this->priceModel = $pricing;
        $this->commonService = $commonService;
    }

    public function store($params)
    {
        $data['name'] = $params['name'];
        $data['value'] = (int)$params['value'];
        $data['is_value_in_percentage'] = $params['is_value_in_percentage'] == 'true' ? true : false;
        $data['is_base_ticket_charge'] = $params['is_base_ticket_charge'] == 'true' ? true : false;
        return $this->priceModel->store($data);
    }

    public function list()
    {
        return $this->priceModel->getAllPricing();
    }

    public function calculateTicketCharge($num)
    {

        $basePrices = $this->priceModel->getPricing(true);
        $baseCharge = 0;
        $totalCharge = 0;
        $priceArray = [];
        foreach ($basePrices as $price) {
            $baseCharge += $num * $price->value;
            $priceArray[] = ['id' => $price->id, 'name' => $price->name, 'amount' => $baseCharge];
            $totalCharge += $baseCharge;
        }
        $model = new Pricing();
        $additionalCharges = $model->getPricing();
        foreach ($additionalCharges as $charge) {
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
}
