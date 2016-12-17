<?php

namespace App\Http\Middleware;
use App\Models\User;

class UserAPI
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
		if($request->header('PHP_AUTH_USER') == $token) {
			$userToken = $request->header('PHP_AUTH_PW', '');
			$user = User::where('user_token', $userToken)->first();
			if ($user == null) {
				return \Response::json(array('success'=>false,'message'=>'UNAUTHORIZED'));
			} else {
				return $next($request);
			}
		}
		else
			return \Response::json(array('success'=>false,'message'=>'API Pass not valid'));
	}

}