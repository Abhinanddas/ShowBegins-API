<?php

namespace App\Http\Controllers;

use App\Services\CommonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\SignUpService;

class SignUpController extends Controller
{
    public function signup(Request $request, SignUpService $signUpService)
    {
        $params = $request->all();
        $requiredFields = [
            'email' => 'bail|required|email|unique:App\Models\User,email',
            'password' => 'bail|required|min:8|alpha_num',
            'name' => 'required|min:2',
        ];
        $validator = Validator::make($params, $requiredFields);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => CommonService::getErrorMessagesFromValidator($validator->errors())]);
        }

        $userId = $signUpService->signUpUser($params);

        if (!$userId) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.signup_failure')]);
        }

        return response()->json(['status' => 'success', 'msg' => trans('messages.signup_success'), 'data' => ['user_id' => $userId]]);
    }
}
