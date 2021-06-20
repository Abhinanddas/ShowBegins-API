<?php

namespace App\Services;

use App\Models\Movie as Movie;
use App\Services\CommonService as CommonService;
use App\Repositories\MovieRepository as MovieRepository;

class MovieService
{
    private $movieModel;
    protected $movieRepo;

    public function __construct(Movie $movie, MovieRepository $movieRepo)
    {
        $this->movieModel = $movie;
        $this->movieRepo = $movieRepo;
    }

    public function add($request)
    {
        $request->validate([
            'name' => 'required',
            'language'=>'required',
            'rating' => 'required',
        ]);

        $data =[
            'name'=>$request->name,
            'language'=>$request->language,
            'rating'=>$request->rating,
        ];

        return  $this->movieRepo->create($data);
    }

    public function listAllMovies(){
        return $this->movieRepo->listAllMovies();
    }

    public function listActiveMovies(){
        return $this->movieModel->listActiveMovies();
    }

}
