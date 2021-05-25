<?php

namespace App\Services;

use App\Models\Show as Show;
use App\Services\CommonService as CommonService;

class MovieService
{
    private $showModel;

    public function __construct(Show $show)
    {
        $this->showModel = $show;
    }

    public function addShow($params)
    {
        $fields = [
            'name' => $params['name'],
            'is_active' => true,
        ];

        return $this->showModel->saveMovie($fields);
    }

    public function listShows(){
        return $this->movieModel->listAllMovies();
    }

    

}
