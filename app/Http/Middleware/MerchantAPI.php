<?php

namespace App\Http\Middleware;


use App\Models\Merchant;

class MerchantAPI
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, $next)
	{
		//$token = env('AUTH_TOKEN', 'NDOWgOhURXwew5UHFI5suSCK');
		$token = $request->header('PHP_AUTH_PW');
		$merchant = Merchant::where('merchant_key',$token)->first();

		if(count($merchant) > 0) {
			$request->attributes->add(['merchant_token' => $merchant]);
			return $next($request);
		}
		else
			return \Response::json(array('success'=>false,'message'=>'Login Error'));
	}

}