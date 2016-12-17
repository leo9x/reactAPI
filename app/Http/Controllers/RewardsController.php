<?php

namespace App\Http\Controllers;

use App\Models\Reward;

class RewardsController extends Controller {
	public function getListReward()
	{
		$rewards = Reward::with('merchant_data')
			->orderBy('merchant_id', 'DESC')
			->orderBy('id', 'ASC')->get();
		$data = [];
		foreach ($rewards as $reward) {
			
		}

	}
}
