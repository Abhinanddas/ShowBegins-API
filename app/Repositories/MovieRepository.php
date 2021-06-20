<?php

namespace App\Repositories;

use App\Models\Movie;
use Error;

class MovieRepository
{
    public function create($data)
    {
        return Movie::insert($data);
    }



    public function listAllMovies()
    {
        return Movie::select('id', 'name', 'language', 'rating')
            ->where('is_deleted', false)
            ->orderBy('id','desc')
            ->get();
    }
}
