<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function checkApiStatus(Request $request)
    {
        return Helper::prettyApiResponse(message: trans('messages.api_success_status'));
    }

    public function validateSession(Request $request)
    {
        return Helper::prettyApiResponse(message: trans('messages.valid_session'));
    }
}
