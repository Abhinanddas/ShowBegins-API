<?php

namespace App\Http\Controllers;

use App\Http\Helper;
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
        return Helper::prettyApiResponse(
            trans('messages.login_success'),
            'success',
            $this->loginService->login($request)
        );
    }

    public function getRefreshToken(Request $request)
    {
        $userId = $request->session()->get('user')['id'];
        $accessTokens = $this->loginService->handleAccessTokens($userId);
        $data = [
            'user_id' => $userId,
            'access_token' => $accessTokens['access_token'],
            'refresh_token' => $accessTokens['refresh_token'],
            'token_expires_at' => $accessTokens['expires_at'],
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
