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
    return 'Banana Team';
});



Route::group(['middleware' => 'api.basic'], function (){
	Route::post('user/register', 'UserController@postRegister');
	Route::post('user/login', 'UserController@postLogin');
	Route::post('user/info', [
		'uses' => 'UserController@getInfoFromCode'
	]);

	// Leo
    Route::post('merchant/login',['uses'=>'MerchantController@login']);
    Route::group(['prefix'=>'merchant','middleware'=>'merchant.api'],function(){
        Route::get('details',['as'=>'merchant.details','uses'=>'MerchantController@details']);
    });

	// End Leo
});
