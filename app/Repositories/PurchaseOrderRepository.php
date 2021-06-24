<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrder;

class PurchaseOrderRepository
{

    public function getPurchaseHistory($showId)
    {
        return PurchaseOrder::select(
            'purchase_orders.num_of_tickets',
            'purchase_orders.amount',
            DB::raw("group_concat(tickets.seat_id separator ',') as seats")
        )
            ->where('purchase_orders.show_id', $showId)
            ->leftJoin('tickets', 'purchase_orders.id', '=', 'tickets.purchase_id')
            ->groupBy('purchase_orders.id')
            ->orderBy('purchase_orders.id', 'desc')
            ->get();
    }

    public function countTicketsSold($showId)
    {
        return PurchaseOrder::where('show_id', $showId)
            ->where('is_refunded', false)
            ->where('is_deleted', false)
            ->sum('num_of_tickets');
    }
}
