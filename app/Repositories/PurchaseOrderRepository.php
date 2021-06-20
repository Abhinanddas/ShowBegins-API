<?php

namespace App\Repositories;

use App\Models\PurchaseOrder;

class PurchaseOrderRepository{

    public function getPurchaseHistory($showId){
        return PurchaseOrder::select('num_of_tickets','amount')
        ->where('show_id', $showId)
        ->orderBy('id','desc')
        ->get();
    }
}
