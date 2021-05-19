<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginWebController extends Controller
{
    public function showLogin()
    {
        $data = ['title' => 'Login'];
        return View::make('pages/login')->with($data);
    }
}
