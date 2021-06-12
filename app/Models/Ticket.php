<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Helper;

class Ticket extends Model
{
    protected $table = 'tickets';

    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }

    public function fetechTicketsBooked($showId, $screenId, $movieId)
    {
        $query = $this->tableObject
            ->select('seat_id as seatId')
            ->where('is_deleted', false);

        if ($screenId) {
            $query->where('screen_id', $screenId);
        }

        if ($showId) {
            $query->where('show_id', $showId);
        }

        if ($movieId) {
            $query->where('movie_id', $movieId);
        }

        return $query->get();
    }

    public function store($data)
    {
        $this->tableObject->insert($data);
    }
}
