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
		$result->name = $this->name;
		$result->logo = $this->logo;
	}
}
