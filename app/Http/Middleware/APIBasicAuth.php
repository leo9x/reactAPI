<?php

namespace App\Http\Middleware;


class APIBasicAuth
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
		echo '<pre>';
		print_r($request->header());
		echo '</pre>';
		die;
		$token = env('AUTH_TOKEN', 'NDOWgOhURXwew5UHFI5suSCK/TJiY6BmsXSDNZDfVBYc5626ab0b2039c3ad8aac844fc5c2a98');
		if($request->header('PHP_AUTH_USER') == $token)
			return $next($request);
		else
			return \Response::json(array('success'=>false,'message'=>'API Pass not valid'));
	}

}