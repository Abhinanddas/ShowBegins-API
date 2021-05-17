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
    private $commonService;
    public function __construct(LoginService $loginService, CommonService $commonService)
    {
        $this->loginService =    $loginService;
        $this->commonService =    $commonService;
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
            return response()->json(['status' => 'erorr', 'msg' => $this->commonService->getErrorMessagesFromValidator($validator->errors())]);
        }

        $userId = $this->loginService->login($params['email'], $params['password']);

        if (!$userId) {
            return response()->json(['status' => 'error', 'msg' => trans('messages.login_failure')]);
        }

        $accessTokens = $this->loginService->handleAccessTokens($userId);
        $data= [
            'user_id'=>$userId,
            'access_token'=>$accessTokens['access_token'],
            'refresh_token'=>$accessTokens['refresh_token'],
        ];
        return response()->json(['status'=>'success','msg'=>trans('meesages.login_success'),'data'=>$data]);
    }

    public function getRefreshToken(Request $request){

        dump($request->session()->all()['user']);die;
    }
}
