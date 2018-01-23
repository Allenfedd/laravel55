<?php


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
Route::group(['namespace' => 'API'], function () {
    //Auth
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');

        Route::group(['middleware' => 'jwt.auth'], function () {
            Route::post('logout', 'AuthController@logout');
            Route::post('refresh', 'AuthController@refreshToken');

            Route::get('me', 'AuthController@me');
        });
    });
});
