<?php

namespace App\Http\Middleware;


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
		$token = env('AUTH_TOKEN', 'NDOWgOhURXwew5UHFI5suSCK');
		if($request->header('PHP_AUTH_USER') == $token)
			return $next($request);
		else
			return \Response::json(array('success'=>false,'message'=>'API Pass not valid'));
	}

}