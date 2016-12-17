<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Input;
use Validator;
use Response;
use Hash;

class UserController extends ControllerBase
{
	public function postRegister()
	{
		$input = Input::all();
		$rule = [
			'name' => 'required',
			'phone' => 'required|numeric|unique:users,phone|digits_between:10,11',
		    'password' => 'required',
		    'confirm_password' => 'required|same:password',
		];
		$validator = Validator::make($input, $rule);
		if (!$validator->fails()) {
			$user = new User();
			$user->name = $input['name'];
			$user->phone = $input['phone'];
			$user->password = Hash::make($input['password']);
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
}
