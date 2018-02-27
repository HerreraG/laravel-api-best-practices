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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

use App\Entities\User;

$api = app('Dingo\Api\Routing\Router');

$api->version(config('api.version'), ['namespace' => 'App\Http\Controllers\Api'], function($api) {
    $api->get('test', function () {
        echo 'hello';
    });

    $api->group(['namespace' => 'Auth'], function ($api) {
        $api->post('login', 'AuthController@login')->name('auth.login');
        $api->get('logout', 'AuthController@logout')->name('auth.logout');
    });

    /* Users */
    $api->group(['namespace' => 'User'], function ($api) {
        $api->post('users', 'UserController@save')->name('users.save');
        $api->get('users', 'UserController@getAll')->name('users.getAll');
        $api->get('users/{id}', 'UserController@getById')->name('users.getById');
        $api->delete('users/delete/{id}', 'UserController@delete')->name('users.delete');
    });
});
