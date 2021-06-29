<?php

namespace App\Services;

use App\Models\Pricing as Pricing;
use App\Services\CommonService as CommonService;
use App\Services\PricingService;
use DateTime;
use Illuminate\Support\Facades\Validator;
use App\Repositories\PriceRepository;

class PricePackageService
{
    private $priceModel;
    private $commonService;
    private $pricingService;
    protected $priceRepo;

    public function __construct(Pricing $pricing, CommonService $commonService, PricingService $pricingService, PriceRepository $priceRepo)
    {
        $this->priceModel = $pricing;
        $this->commonService = $commonService;
        $this->pricingService = $pricingService;
        $this->priceRepo = $priceRepo;
    }

    public function store($request)
    {
        $request->validate([
            'name' => 'required',
            'base_charges' => 'required',
            'other_charges' => 'required',
        ]);

        $this->validateBaseCharges($request->base_charges);

        $priceMasterFields = ['name' => $request->name, 'is_deleted' => false];
        $pricePackageMasterId = $this->priceRepo->savePricePackageMaster($priceMasterFields);

        $pricePackageMappingData = [];
        foreach ($request->base_charges as $price) {
            $pricePackageMappingData[] = [
                'pricing_package_master_id' => $pricePackageMasterId,
                'pricing_id' => $price,
                'is_base_charge' => true,
            ];
        }
        foreach ($request->other_charges as $price) {
            $pricePackageMappingData[] = [
                'pricing_package_master_id' => $pricePackageMasterId,
                'pricing_id' => $price,
                'is_base_charge' => false,
            ];
        }

        $this->priceRepo->savePricingPackageMapping($pricePackageMappingData);
        return;
    }

    public function validateBaseCharges($baseChargeIds)
    {

        $percentagePriceExists = $this->pricingService->checkForPercentageType($baseChargeIds);

        if ($percentagePriceExists) {
            throw new \App\Exceptions\InvalidFormDataException(trans('messages.form_error.pricing_package.base_charge_violated_with_percentage_type'));
        }
        return;
    }

    public function list(){
        return $this->priceRepo->listPricePackage();
    }
}
