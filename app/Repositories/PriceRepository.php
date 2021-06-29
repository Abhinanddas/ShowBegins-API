<?php

namespace App\Repositories;

use App\Models\Pricing;
use App\Models\PricingPackageMapping;
use App\Models\PricingPackageMaster;
use Illuminate\Support\Facades\DB;

class PriceRepository
{

    public function checkForPercentageType($priceIds)
    {
        return Pricing::whereIn('id', $priceIds)
            ->where('is_value_in_percentage', true)
            ->first();
    }

    public function savePricePackageMaster($data)
    {
        return PricingPackageMaster::insertGetId($data);
    }

    public function savePricingPackageMapping($data)
    {
        return PricingPackageMapping::insert($data);
    }

    public function listPricePackage()
    {
        $resultData = [];
        $data =  PricingPackageMaster::from('pricing_package_masters as master')
            ->leftJoin('pricing_package_mappings as mapping', 'mapping.pricing_package_master_id', '=', 'master.id')
            ->leftJoin('pricings as p', 'p.id', '=', 'mapping.pricing_id')
            ->where('master.is_deleted', false)
            ->groupBy('master.id')
            ->orderBy('master.id', 'desc')
            ->select(
                'master.id',
                'master.name',
                DB::raw("concat('[', group_concat(JSON_OBJECT('id',mapping.id,'is_base_charge',mapping.is_base_charge,'name',p.name)),']') as price")
            )
            ->get();

        foreach ($data as $price) {
            $resultData[] = [
                'id' => $price->id,
                'name' => $price->name,
                'price' => json_decode($price->price),
            ];
        }
        return $resultData;
    }

    public function getPricePackageDetails($id)
    {
        $resultData = [];
        $data =  PricingPackageMaster::from('pricing_package_masters as master')
            ->where('master.id', $id)
            ->where('master.is_deleted', false)
            ->leftJoin('pricing_package_mappings as mapping', 'mapping.pricing_package_master_id', '=', 'master.id')
            ->leftJoin('pricings as p', 'p.id', '=', 'mapping.pricing_id')
            ->groupBy('mapping.is_base_charge')
            ->select(
                'mapping.is_base_charge',
                DB::raw("concat('[',group_concat(JSON_OBJECT('id',p.id,'name',p.name,'value', p.value,'is_percentage', p.is_value_in_percentage)),']') as price_details")
            )
            ->get();

        $baseCharges = [];
        $otherCharges = [];
        foreach ($data as $price) {
            if ($price->is_base_charge) {
                $baseCharges = json_decode($price->price_details);
            } else {
                $otherCharges = json_decode($price->price_details);
            }
        }
        return ['base_charges' => $baseCharges, 'other_charges' => $otherCharges];
    }
}
