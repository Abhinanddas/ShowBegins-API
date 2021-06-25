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

    public function remove($id)
    {
        return Screen::where('id', $id)->update([
            'is_deleted' => true,
        ]);
    }

    public function get($id){
        return Screen::select('id','name','seating_capacity')->where('id',$id)->first();
    }

    public function update($id, $params)
    {
        return Screen::where('id', $id)
            ->update([
                'name' => $params['name'],
                'seating_capacity' => $params['seating_capacity'],
            ]);
    }
}
