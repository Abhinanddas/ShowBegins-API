<?php

namespace App\Http\Controllers;

use App\Services\CommonServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\LoginService;
use App\Services\CommonService;

class LoginController extends Controller
{

    private $loginService;
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function login(Request $request)
    {
        $params = $request->all();
        $requiredFields = [
            'email' => "bail|required|email",
            'password' => 'required',
        ];
        $validator = Validator::make($params, $requiredFields);

        if ($validator->fails()) {
            return response()->json(['status' => 'erorr', 'msg' => CommonService::getErrorMessagesFromValidator($validator->errors())]);
        }

        $userData = $this->loginService->login($params['email'], $params['password']);

        if (!$userData) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.login_failure')]);
        }

        $accessTokens = $this->loginService->handleAccessTokens($userData['id']);
        $data = [
            'user_data' => $userData,
            'access_token' => $accessTokens['access_token'],
            'refresh_token' => $accessTokens['refresh_token'],
        ];
        return response()->json(['status' => 'success', 'msg' => trans('messages.login_success'), 'data' => $data]);
    }

    public function getRefreshToken(Request $request)
    {
        $userId = $request->session()->get('user')['id'];
        $accessTokens = $this->loginService->handleAccessTokens($userId);
        $data = [
            'user_id' => $userId,
            'access_token' => $accessTokens['access_token'],
            'refresh_token' => $accessTokens['refresh_token'],
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function logout(Request $request)
    {
        $userId = $request->session()->get('user')['id'];
        $this->loginService->logout($userId);
        return response()->json(['status' => 'success', 'msg' => trans('messages.logout_success')]);
    }
}
