<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Input;
use Validator;
use Response;
use Hash;
use Auth;

class UserController extends ControllerBase
{
	public function postRegister()
	{
		$input = Input::all();
		$rule = [
			'name' => 'required',
			'email' => 'email|unique:users,email',
			'phone' => 'numeric|unique:users,phone|digits_between:10,11',
		    'password' => 'required',
		    'confirm_password' => 'required|same:password',
		];
		$validator = Validator::make($input, $rule);
		if (!$validator->fails()) {
			if (!isset($input['email']) && !isset($input['phone'])) {
				return Response::json([
					'success'=>false,
				    'message' => 'Push phone or email to register',
				]);
			}
			$user = new User();
			$user->name = $input['name'];
			$user->phone = isset($input['phone']) ? $input['phone'] : '';
			$user->email = isset($input['email']) ? $input['email'] : '';
			$user->password = Hash::make($input['password']);
			$user->user_token = md5(time() . rand(0,time()));
			$user->qr_code = md5(time(). rand(0, time()));
			$user->save();
			return Response::json([
				'success'=>true,
			    'message'=> 'Register successfully',
			]);
		} else {
			return Response::json([
				'success'=>false,
			    'message'=> $this->resolveFailMessage($validator->messages()),
			]);
		}
    }

	public function postLogin()
	{
		$input = Input::all();
		$rule  = [
			'key' => 'required',
			'password' => 'required',
		];
		$validator = Validator::make($input, $rule);
		if (!$validator->fails()) {
			if (strpos($input['key'], '@') !== false) {
				$key = 'email';
			} else {
				$key = 'phone';
			}
			$check = Auth::attempt([
		         $key => $input['key'],
				'password' => $input['password'],
			]);
			if ($check) {
				$user = Auth::getUser();
				return Response::json([
					'success' => true,
					'user' => [
						'name' => $user->name,
						'email' => $user->email,
						'phone' => $user->phone,
						'user_token' => $user->user_token,
						'point' => $user->point,
						'qr_code' => User::getQrCode($user->qr_code),
						'avatar' => $user->avatar,
					]
				]);
			} else {
				return Response::json([
					'success'=>false,
				    'message' => 'Login fail'
				]);
			}
		} else {
			return Response::json([
				'success'=>false,
			    'message'=> $this->resolveFailMessage($validator->messages()),
			]);
		}
	}
}
