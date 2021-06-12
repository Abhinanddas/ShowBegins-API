<?php

namespace App\Services;

use App\Models\Ticket as Ticket;
use App\Services\CommonService as CommonService;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class TicketService
{
    private $ticketModel;

    public function __construct(Ticket $ticket)
    {
        $this->ticketModel = $ticket;
    }


    public function fetechTicketsBooked($showId = false, $screenId = false, $movieId = false)
    {
        $result = $this->ticketModel->fetechTicketsBooked($showId, $screenId, $movieId);

        $bookedSeats = [];

        foreach ($result as $ticket) {
            $bookedSeats[] = $ticket->seatId;
        }

        return $bookedSeats;
    }

    public function saveTickets($selectedSeats, $movieId, $screenId, $showId, $purchaseOrderId)
    {

        $ticketArray = [];
        foreach ($selectedSeats as $seat) {
            $ticketArray[] = [
                'seat_id' => $seat,
                'show_id' => $showId,
                'screen_id' => $screenId,
                'movie_id' => $movieId,
                'purchase_id' => $purchaseOrderId,
            ];
        }
        $this->ticketModel->store($ticketArray);
    }
}
