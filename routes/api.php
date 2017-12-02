<?php

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

Route::post('/auth/logout', 'OauthController@logout');

Route::get('/auth/status', 'OauthController@checkAuthStatus');

Route::get('/token/session', 'OauthController@getSessionToken');

Route::get('/repository/test', 'OauthController@testRepository');
