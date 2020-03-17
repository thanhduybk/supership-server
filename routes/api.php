<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('me', 'AuthController@me');
    });
});

Route::apiResource('repositories', 'RepositoryController')
    ->middleware('auth:api');

Route::apiResource('orders', 'OrderController')
    ->middleware('auth:api');

Route::get('provinces', 'AddressController@allProvinces');
Route::get('districts', 'AddressController@allDistricts');
Route::get('wards', 'AddressController@allWards');

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => sprintf("No handler found for the request %s-%s", request()->getMethod(), request()->getPathInfo()),
    ], 404);
});
