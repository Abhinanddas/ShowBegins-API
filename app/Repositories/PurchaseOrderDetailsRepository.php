<?php

namespace App\Repositories;

use App\Models\PurchaseOrderDetails;

class PurchaseOrderDetailsRepository{

    public function save($data){
        return PurchaseOrderDetails::insert($data);
    }
}
