<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Web\LoginWebController@dashboard');
Route::get('/login', 'Web\LoginWebController@showLogin');
Route::post('/do-login', 'Web\LoginWebController@doLogin');
