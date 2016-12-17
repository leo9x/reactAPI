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
	Route::get('user/get-info/{id}', 'UserController@getInfo');

	Route::get('/reward', 'RewardsController@getListReward');
	Route::get('/reward-merchant/{id}', 'RewardsController@getRewardByMerchant');
	Route::get('/reward/detail/{id}', 'RewardsController@getDetail');

	Route::group(['middleware'=>'user.login'],function(){
		Route::get('/user/merchant', 'UserController@getListMerchant');
		Route::get('/user/merchant/detail/{id}', 'UserController@getMerchantDetail');
		Route::get('/user/merchant/reward/{id}', 'UserController@getListReward');
	});

	// Leo
    Route::post('merchant/login',['uses'=>'MerchantController@login']);
    Route::group(['prefix'=>'merchant','middleware'=>'merchant.api'],function(){
        Route::get('details',['as'=>'merchant.details','uses'=>'MerchantController@details']);
	    Route::get('/rewards', 'MerchantController@getListReward');
	    Route::post('user/info', [
		    'uses' => 'UserController@getInfoFromCode'
	    ]);
	    Route::post('user/adjust-point', 'UserController@postAdjustPoint');
    });

	// End Leo
});
