<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ControllerBase extends Controller
{
    public function resolveFailMessage($messages) {
	    $msg = $messages->getMessages();
	    $result = [];
	    foreach ($msg as $item) {
		    $result[] = $item[0];
	    }

	    return $result;
    }
}
