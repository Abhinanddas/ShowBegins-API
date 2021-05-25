<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\MovieService;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    public function addMovie(Request $request, MovieService $movieService)
    {

        $params = $request->all();
        $requiredFields = [
            'name' => 'required',
        ];

        $validator = Validator::make($params, $requiredFields);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => CommonService::getErrorMessagesFromValidator($validator->errors())]);
        }

        $movie = $movieService->addMovie($params);

        if (!$movie) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.insert_failure', ['item' => 'Movie'])]);
        }

        $data = ['id' => $movie];
        return response()->json(['status' => 'success', 'msg' => trans('messages.insert_success', ['item' => 'Movie']), 'data' => $data]);
    }

    public function listAllMovies(Request $request, MovieService $movieService)
    {

        return response()->json(['status' => 'success', 'data' => $movieService->listAllMovies()]);
    }

    public function listActiveMovies(Request $request, MovieService $movieService)
    {

        return response()->json(['status' => 'success', 'data' => $movieService->listActiveMovies()]);
    }
}
