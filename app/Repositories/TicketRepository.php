<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository
{

    public function getShowTicketDetails($showId)
    {

        return [
            'seats' => Ticket::where('tickets.show_id', $showId)
                ->where('is_deleted', false)
                ->pluck('seat_id')
                ->toArray()
        ];
    }
}
