<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Merchant extends Model
{
    use SoftDeletes;

	public function getMerchantInfo($login = false)
	{
		$r = new \stdClass();
		$r->id = $this->id;
		$r->name = $this->name;
		$r->email = $this->email;
		$r->logo = env('APP_URL', 'https://api.9box.co') . $this->logo;
		$r->color = $this->color;
		$r->description = $this->description;
		$r->short_description = $this->short_description;
		$r->latitude = $this->latitude;
		$r->longitude = $this->longitude;
		$r->background = env('APP_URL', 'https://api.9box.co') . $this->background;
		if($login){
			$r->merchant_key = $this->merchant_key;
		}

		return $r;
	}
}
