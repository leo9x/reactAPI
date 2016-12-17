<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;

class UserController extends ControllerBase
{
	public function postRegister()
	{
		$input = Input::all();
		echo '<pre>';
		print_r($input);
		echo '</pre>';
		die;
    }
}
