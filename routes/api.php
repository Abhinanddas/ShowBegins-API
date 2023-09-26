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
If you can see this, CICD works
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
        Route::put('screen/{sceenId}','ScreenController@update');
        Route::post('shows','ShowController@addShow');
        Route::get('shows','ShowController@index');
        Route::get('show/{showId}','ShowController@getShowDetails');
        Route::get('dashboard/shows','ShowController@getShowsForDashboard');
        Route::get('ticket-charge','PricingController@getTicketCharge');
        Route::get('tickets/booked/{showId}','ShowController@getBookedSeatDetails');
        Route::get('screen/{id}','ScreenController@index');
        Route::delete('screens/{id}','ScreenController@removeScreens');
        Route::apiResource('pricing', 'PricingController');
        Route::apiResource('purchases', 'PurchaseOrderController');
        Route::apiResource('purchase-order', 'PurchaseOrderController');
        Route::apiResource('ticket', 'TicketController');
        Route::apiResource('price-package', 'PricePackageController');
    }
);
