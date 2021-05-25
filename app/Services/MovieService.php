<?php

namespace App\Services;

use App\Models\Movie as Movie;
use App\Services\CommonService as CommonService;

class MovieService
{
    private $movieModel;

    public function __construct(Movie $movie)
    {
        $this->movieModel = $movie;
    }

    public function addMovie($params)
    {
        $fields = [
            'name' => $params['name'],
            'is_active' => true,
        ];

        return $this->movieModel->saveMovie($fields);
    }

    public function listAllMovies(){
        return $this->movieModel->listAllMovies();
    }

    public function listActiveMovies(){
        return $this->movieModel->listActiveMovies();
    }

}
