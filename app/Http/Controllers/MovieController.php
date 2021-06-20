<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\MovieService;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    protected $movieService;
    protected $commonService;

    public function __construct(MovieService $movieService, CommonService $commonService)
    {
        $this->movieService = $movieService;
        $this->commonService = $commonService;
    }
    public function addMovie(Request $request, MovieService $movieService)
    {

        return Helper::prettyApiResponse(
            trans('messages.insert_success', ['item' => 'Movie']),
            'success',
            $this->movieService->add($request)
        );
    }

    public function listAllMovies(Request $request)
    {

        return response()->json(['status' => 'success', 'data' => $this->movieService->listAllMovies()]);
    }

    public function listActiveMovies(Request $request, MovieService $movieService)
    {

        return response()->json(['status' => 'success', 'data' => $movieService->listActiveMovies()]);
    }
}
