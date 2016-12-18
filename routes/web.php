<?php
Route::get('/test/{id}',['uses'=>'PassbookController@test']);
Route::get('/', function () {
    return 'Banana Team';
});

Route::group(['prefix' => 'passbook'], function () {
	Route::post('{version}/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}/{serialNumber}', ['uses' => 'PassbookController@register']);
	Route::get('{version}/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}', ['uses' => 'PassbookController@listSerialNumbers']);
	Route::get('{version}/passes/{passTypeIdentifier}/{serialNumber}', ['uses' => 'PassbookController@getPassData']);
	Route::post('{version}/log', ['uses' => 'PassbookController@logPassbookError']);
	Route::post('push-notification', ['uses' => 'PassbookController@pushNotification']);
	Route::delete('{version}/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}/{serialNumber}', ['uses' => 'PassbookController@unRegister']);
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
