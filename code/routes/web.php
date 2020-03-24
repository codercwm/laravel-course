<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', 'Home\IndexController@test');

Route::resource('users', 'Home\UsersController');

Route::get('log','Home\UsersController@log');

Route::get('avatar','Home\IndexController@avatar');


Route::middleware('auth:api')->group(function($router){
    $router->get('/jwtTest','Home\UsersController@jwtTest');
});

