<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Merchant extends Model
{
    use SoftDeletes;

	public function getMerchantInfo()
	{
		$r = new \stdClass();
		$r->id = $this->id;
		$r->name = $this->name;
		$r->email = $this->email;
		$r->logo = env('APP_URL', 'https://api.9box.co') . $this->logo;
		$r->color = $this->color;
		$r->description = $this->description;
		$r->latitude = $this->latitude;
		$r->longitude = $this->longitude;
		$r->background = env('APP_URL', 'https://api.9box.co') . $this->background;

		return $r;
	}
}
