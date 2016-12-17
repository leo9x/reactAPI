<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
    use SoftDeletes;
	protected $table = 'rewards';

	public function merchant_data()
	{
		$this->belongsTo('App\Models\Merchant', 'merchant_id');
	}

	public function getRewardInfo()
	{
		$result = new \stdClass();
		$result->id = $this->id;
		$result->name = $this->name;
		$result->logo = env('APP_URL', 'https://api.9box.co') . $this->logo;
		$result->description = $this->description;
		$result->point = $this->point;
		$result->quantity = $this->quantity;
		$result->merchant_id = $this->merchant_id;
		return $result;
	}
}
