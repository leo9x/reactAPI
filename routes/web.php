<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-git', function () {
	echo '<pre>';
	print_r('GIT-11');
	echo '</pre>';
	die;
});

Route::group(['middleware' => 'api.basic'], function (){
	Route::post('user/register', 'UserController@postRegister');
	Route::post('user/login', 'UserController@postLogin');
});
