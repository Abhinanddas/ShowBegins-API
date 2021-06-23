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
Route::post('logout','LoginController@logout');
Route::middleware(['session.validator'])->group(
    function () {
        Route::post('get-referesh-token', 'LoginController@getRefreshToken');
        Route::post('session-check','ApiController@validateSession');
        Route::post('movies','MovieController@addMovie');
        Route::get('movies','MovieController@listAllMovies');
        Route::post('list-active-movies','MovieController@listActiveMovies');
        Route::post('screens','ScreenController@addScreen');
        Route::get('screens','ScreenController@listAllScreens');
        Route::post('shows','ShowController@addShow');
        Route::get('shows','ShowController@index');
        Route::get('show-details/{showId}','ShowController@getShowDetails');
        Route::get('dashboard/shows','ShowController@getShowsForDashboard');
        Route::apiResource('pricing', 'PricingController');
        Route::apiResource('purchases', 'PurchaseOrderController');
        Route::get('ticket-charge/{num}','PricingController@getTicketCharge');
        Route::apiResource('purchase-order', 'PurchaseOrderController');
        Route::apiResource('ticket', 'TicketController');
        Route::get('tickets/booked/{showId}','ShowController@getBookedSeatDetails');
    }
);
