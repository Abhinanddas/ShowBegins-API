<?php

namespace App\Repositories;

use App\Models\Screen;

class ScreenRepository
{

    public function getScreenSeatCount($screenIds)
    {
        return Screen::select(
            'id',
            'seating_capacity as seat_count'
        )
            ->whereIn('id', $screenIds)
            ->get();
    }
}
