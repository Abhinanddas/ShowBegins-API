<?php

namespace App\Repositories;

use App\Models\Show;

class ShowRepository
{

    public function getShowDetails($showId)
    {

        return Show::select(
            'shows.id as show_id',
            'shows.show_time as show_time',
            'movies.name as movie_name',
            'screens.name as screen_name'
        )
            ->where('shows.id', $showId)
            ->leftJoin('movies', 'shows.movie_id', '=', 'movies.id')
            ->leftJoin('screens', 'shows.screen_id', '=', 'screens.id')
            ->first();
    }
}
