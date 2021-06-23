<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\ShowService;
use Illuminate\Support\Facades\Validator;

class ShowController extends Controller
{
    protected $showService;
    protected $commonService;
    public function __construct(ShowService $showService, CommonService $commonService)
    {
        $this->showService = $showService;
        $this->commonService = $commonService;
    }
    public function addShow(Request $request)
    {

        $params = $request->all();

        $isValid = $this->showService->validateShowParams($params);

        if (!$isValid) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.invalid_input')]);
        }

        $params = $this->showService->processShowTimeParam($params);

        $isValidShowTimings = $this->showService->validateShowTimings($params);

        if (!$isValidShowTimings) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.invalid_show_timing')]);
        }

        $addShow = $this->showService->addShow($params);

        if (!$addShow) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.insert_failure', ['item' => 'Show'])]);
        }

        return response()->json(['status' => 'success', 'msg' => trans('messages.insert_success', ['item' => 'Show'])]);
    }

    public function index(Request $request)
    {

        return response()->json(['status' => 'success', 'data' => $this->showService->listShows($request)]);
    }

    public function getShowsForDashboard()
    {
        return response()->json(['status' => 'success', 'data' => $this->showService->getShowsForDashboard()]);
    }

    public function getShowDetails(Request $request, $showId)
    {
        return Helper::prettyApiResponse(trans('messages.shows.show_details_success'), 'success', $this->showService->getShowDetails($showId));
    }

    public function getBookedSeatDetails(Request $request, $showId)
    {
        return Helper::prettyApiResponse(trans('messages.shows.ticket_details_success'), 'success', $this->showService->getBookedSeatDetails($showId));
    }

}
