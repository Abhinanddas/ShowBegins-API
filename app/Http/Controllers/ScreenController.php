<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\ScreenService;
use Illuminate\Support\Facades\Validator;

class ScreenController extends Controller
{
    protected $screenService;

    public function __construct(ScreenService $screenService)
    {
        $this->screenService = $screenService;
    }

    public function addScreen(Request $request, ScreenService $screenService)
    {

        $params = $request->all();
        $requiredFields = [
            'name' => 'required',
            'seating_capacity' => 'required|integer',
        ];

        $validator = Validator::make($params, $requiredFields);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => CommonService::getErrorMessagesFromValidator($validator->errors())]);
        }

        $screen = $screenService->addScreen($params);

        if (!$screen) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.insert_failure', ['item' => 'Screen'])]);
        }

        $data = ['id' => $screen];
        return response()->json(['status' => 'success', 'msg' => trans('messages.insert_success', ['item' => 'Screen']), 'data' => $data]);
    }

    public function listAllScreens(Request $request, ScreenService $screenService)
    {

        return response()->json(['status' => 'success', 'data' => $screenService->listAllScreens()]);
    }
    public function removeScreens($id)
    {
        return Helper::prettyApiResponse(
            trans('messages.remove_success', ['item' => 'Screen']),
            'success',
            $this->screenService->remove($id)
        );
    }

    public function index($id)
    {
        return Helper::prettyApiResponse(
            trans('messages.list_success', ['item' => 'Screen']),
            'success',
            $this->screenService->getScreenDetails($id)
        );
    }

    public function update(Request $request, $id)
    {
        return Helper::prettyApiResponse(
            trans('messages.update_success', ['item' => 'Screen']),
            'success',
            $this->screenService->update($id, $request)
        );
    }
}
