<?php

use App\Http\Resources\DriverCollection;
use Illuminate\Http\Request;

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

Route::group(['prefix' => 'v1'], function() {

    Route::fallback(function(){
        return response()->json(['message' => 'Not Implemented'], 501);
    })->name('api.fallback.501');

    Route::get('/init', 'InitController@index');

    Route::get('/drivers', 'DriverController@index');

    Route::get('/employees', 'EmployeeController@index');

    Route::get('/orders', 'OrderController@index');

    Route::get('/cars', 'CarController@index');

    Route::get('/point-only-for-auth', function(){
        return response()->json(['message' => 'You transferred correct token']);
    })->middleware('auth:api');

});
