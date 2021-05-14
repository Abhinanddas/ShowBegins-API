<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\LoginService;

class LoginController extends Controller
{
    public function login(Request $request, LoginService $loginService)
    {

        $params = $request->all();
        $requiredFields = [
            'email' => "required|email",
            'password' => 'required',
        ];
        $validator = Validator::make($params, $requiredFields);

        if($validator->fails()){
            return response()->json(['status'=>'erorr','msg'=>$loginService->getLoginErrorMessage($validator->errors())]);
        }

        $userId = $loginService->login($params['email'],$params['password']);

        if(!$userId){
            return response()->json(['status'=>'error','msg'=>trans('messages.login_failure')]);
        }
        
        // $jwtToken = JWTTokenService::createJWTToken($params['email'], $userId);

        
    }
}
