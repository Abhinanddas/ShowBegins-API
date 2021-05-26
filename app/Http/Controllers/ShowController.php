<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\ShowService;
use Illuminate\Support\Facades\Validator;

class ShowController extends Controller
{
    public function addShow(Request $request, ShowService $showService)
    {

        $params = $request->all();

        $isValid = $showService->validateShowParams($params);

        if (!$isValid) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.invalid_input')]);
        }

        $params = $showService->processShowTimeParam($params);

        $isValidShowTimings = $showService->validateShowTimings($params);

        if (!$isValidShowTimings) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.invalid_show_timing')]);
        }

        $addShow = $showService->addShow($params);

        if (!$addShow) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.insert_failure', ['item' => 'Show'])]);
        }

        return response()->json(['status' => 'success', 'msg' => trans('messages.insert_success', ['item' => 'Show'])]);
    }

    public function listActiveShows(Request $request, ShowService $showService)
    {

        return response()->json(['status' => 'success', 'data' => $showService->listActiveShows()]);
    }

    public function listAllShows(Request $request, ShowService $showService)
    {

        return response()->json(['status' => 'success', 'data' => $showService->listAllShows()]);
    }
}
