<?php

use App\Http\Middleware\SessionValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PricingController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'LoginController@login');
Route::post('signup', 'SignUpController@signup');
Route::get('api-status','ApiController@checkApiStatus');
Route::middleware(['session.validator'])->group(
    function () {
        Route::post('get-referesh-token', 'LoginController@getRefreshToken');
        Route::post('logout','LoginController@logout');
        Route::post('session-check','ApiController@validateSession');
        Route::post('add-movie','MovieController@addMovie');
        Route::post('list-all-movies','MovieController@listAllMovies');
        Route::post('list-active-movies','MovieController@listActiveMovies');
        Route::post('add-screen','ScreenController@addScreen');
        Route::post('list-all-screens','ScreenController@listAllScreens');
        Route::post('add-show','ShowController@addShow');
        Route::post('list-active-shows','ShowController@listActiveShows');
        Route::post('list-all-shows','ShowController@listAllShows');
        Route::apiResource('pricing', 'PricingController');
        Route::get('ticket-charge','PricingController@getTicketCharge');
    }
);
