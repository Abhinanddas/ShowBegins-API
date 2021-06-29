<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Models\PurchaseOrder;
use Error;
use Illuminate\Support\Facades\DB;

class MovieRepository
{
    public function create($data)
    {
        return Movie::insert($data);
    }



    public function listAllMovies()
    {
        $purchaseOrderQuery = PurchaseOrder::select(
            'movie_id',
            DB::raw('sum(amount) as collection'),
            DB::raw('sum(num_of_tickets) as num_of_tickets')
        )
            ->where('is_deleted', false)
            ->where('is_refunded', false)
            ->groupBy('movie_id');


        return Movie::from('movies as m')
            ->select(
                'm.id',
                'm.name',
                'm.language',
                'm.rating',
                DB::raw('count(s.id) as show_count'),
                DB::raw('coalesce(po.collection,0) as collection'),
                DB::raw('coalesce(po.num_of_tickets,0) as num_of_tickets')
            )
            ->leftJoin('shows as s', 'm.id', '=', 's.movie_id')
            ->leftJoinSub($purchaseOrderQuery, 'po', function ($join) {
                $join->on('m.id', '=', 'po.movie_id');
            })
            ->where('m.is_deleted', false)
            ->groupBy('m.id')
            ->orderBy('m.id', 'desc')
            ->get();
    }
}
