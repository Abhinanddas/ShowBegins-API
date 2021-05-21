<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Services\WebServices\LoginWebService as LoginWebService;

class LoginWebController extends Controller
{
    public function showLogin(LoginWebService $loginWebService)
    {
        $result = $loginWebService->checkApiStatus();
        $data = ['title' => 'Login'];
        return View::make('pages/login')->with($data);
    }

    public function doLogin(Request $request, LoginWebService $loginWebService)
    {
        $params = $request->post();
        $result = $loginWebService->login($params['email'], $params['password']);
    }

    public function dashboard()
    {
    }
}
