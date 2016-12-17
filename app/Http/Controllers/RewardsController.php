<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Input;
use Response;


class RewardsController extends Controller
{
	public function getListReward()
	{
		$rewards = Reward::with('merchant_data')
			->orderBy('merchant_id', 'DESC')
			->orderBy('id', 'ASC')->get();
		$data    = [];
		foreach ($rewards as $reward) {
			$data[] = $reward->getRewardInfo;
		}

		return Response::json([
			'success' => true,
			'rewards' => $data,
		]);
	}

	public function getRewardByMerchant($id)
	{
		$rewards = Reward::with('merchant_data')
			->where('merchant_id', $id)
			->orderBy('merchant_id', 'DESC')
			->orderBy('id', 'ASC')->get();
		$data    = [];
		foreach ($rewards as $reward) {
			$data[] = $reward->getRewardInfo;
		}

		return Response::json([
			'success' => true,
			'rewards' => $data,
		]);
	}

	public function getDetail($id)
	{
		$reward = Reward::find($id);
		if ($reward == null) {
			return Response::json([
				'success'=>false,
			    'message'=>'Reward not found',
			]);
		}

		return Response::json([
			'success'=>true,
		    'reward' => $reward->getRewardInfo(),
		]);
	}
}
