<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function checkApiStatus(Request $request)
    {
        return response()->json(['status' => 'success', 'msg' => trans('messages.api_success_status')]);
    }

    public function validateSession(Request $request)
    {
        return response()->json(['status' => 'success', 'msg' => trans('messages.valid_session')]);
    }
}
