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

    public function listActiveShows(Request $request)
    {

        return response()->json(['status' => 'success', 'data' => $this->showService->listActiveShows()]);
    }

    public function listAllShows(Request $request)
    {

        return response()->json(['status' => 'success', 'data' => $this->showService->listAllShows()]);
    }

    public function getShowDetails(Request $request)
    {
        return Helper::prettyApiResponse(trans('messages.shows.show_details_success'),'success',$this->showService->getShowDetails($request));
    }
    
    public function getShowTicketDetails(Request $request){
        return Helper::prettyApiResponse(trans('messages.shows.ticket_details_success'),'success',$this->showService->getShowTicketDetails($request));
    }
}
