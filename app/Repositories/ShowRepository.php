<?php

namespace App\Repositories;

use App\Models\Show;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Services\ShowService;

class ShowRepository
{

    public function getShowDetails($showId)
    {

        return Show::select(
            'shows.id as show_id',
            'shows.show_time as show_time',
            'movies.name as movie_name',
            'screens.name as screen_name',
            'shows.number_of_seats as seat_count',
            'shows.tickets_sold as tickets_sold',
            'shows.booking_status as booking_status'
        )
            ->where('shows.id', $showId)
            ->leftJoin('movies', 'shows.movie_id', '=', 'movies.id')
            ->leftJoin('screens', 'shows.screen_id', '=', 'screens.id')
            ->first();
    }


    public function getShows($isActive = true, $fromDate = null, $toDate = null)
    {
        $query = Show::select(
            'shows.id as show_id',
            'movies.id as movie_id',
            'movies.name as movie_name',
            'screens.name as screen_name',
            'shows.show_time as show_time',
            'shows.screen_id as screen_id',
            'shows.number_of_seats as seat_count',
            'shows.tickets_sold as tickets_sold',
            'shows.booking_status as booking_status'
        )
            ->where('screens.is_deleted', false)
            ->leftJoin('movies', 'shows.movie_id', '=', 'movies.id')
            ->leftJoin('screens', 'shows.screen_id', '=', 'screens.id')
            ->orderBy('movie_id', 'desc')
            ->orderBy('show_time', 'asc')
            ->orderBy('screen_id', 'desc');

        if ($fromDate) {
            // $query->where('shows.show_time', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('shows.show_time', '<=', $toDate);
        }

        if ($isActive) {
            $query->where('shows.is_active', $isActive);
        }
        return $query->get();
    }

    public function getTotalSeats($showId)
    {
        return Show::where('id', $showId)
            ->pluck('number_of_seats')
            ->first();
    }

    public function updateShowStatistics($showId, $ticketsSold, $bookingStatus, $isHouseFull)
    {

        Show::where('id', $showId)
            ->update([
                'tickets_sold' => $ticketsSold,
                'booking_status' => $bookingStatus,
                'is_house_full' => $isHouseFull,
            ]);

        return;
    }
}
