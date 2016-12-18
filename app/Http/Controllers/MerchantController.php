<?php
namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Reward;
use App\Models\UserReward;
use Hash;
use Illuminate\Foundation\Auth\User;
use Input;
use Request;
use Response;
use Validator;

class MerchantController extends ControllerBase
{

	public function getListReward($user_id = null)
	{
		$merchant         = Request::get('merchant_token');
		$rewards = Reward::with('merchant_data')
			->where('merchant_id', $merchant->id)
			->orderBy('merchant_id', 'DESC')
			->orderBy('id', 'ASC')->get();
		$data    = [];
		$count  = count($rewards);
		foreach ($rewards as $reward) {
			if ($user_id == null)
				$data[] = $reward->getRewardInfo();
			else
				$data[] = $reward->getUserRewardInfo($user_id);
		}

		return Response::json([
			'success' => true,
			'total' => $count,
			'rewards' => $data,
		]);
	}

	public function details()
	{
		$merchant         = Request::get('merchant_token');
		$data             = new \stdClass();
		$data->name       = $merchant->name;
		$data->logo       = $merchant->logo;
		$data->color      = trim(preg_replace('/\s\s+/', ' ', $merchant->color));
		$data->latitude   = $merchant->latitude;
		$data->longtitude = $merchant->longtitude;

		return Response::json($data, 200);
	}

	public function login()
	{
		$data  = Request::all();
		$rules = [
			'email'    => 'required',
			'password' => 'required',
		];

		$validator = Validator::make($data, $rules);
		if (!$validator->fails()) {
			$merchant = Merchant::where('email', strtolower($data['email']))->first();
			if (count($merchant) == 0) {
				$return            = [];
				$return['success'] = false;
				$return['message'] = ['email' => 'Email not exists'];

				return Response::json($return, 200);
			}
			if (!Hash::check($data['password'], $merchant->password)) {
				$return            = [];
				$return['success'] = false;
				$return['message'] = ['email' => 'Password not correct'];

				return Response::json($return, 200);
			}

			$return             = [];
			$return['success']  = true;
			$return['data']     = $merchant->getMerchantInfo(true);

			return Response::json($return, 200);
		} else {
			$error = [];
			foreach ($validator->messages()->toArray() as $key => $value) {
				$error[] = $value[0];
			}
			$return            = [];
			$return['success'] = false;
			$return['message'] = $error;

			return Response::json($return, 200);
		}

	}

	public function postRedeem()
	{
		$input = Input::all();
		$rule = [
			'reward_id' => 'required',
		    'user_id' => 'required',
		];
		$validator = Validator::make($input, $rule);
		if (!$validator->fails()) {
			$reward = Reward::find($input['reward_id']);
			if ($reward == null) {
				return Response::json([
					'success'=>false,
				    'message'=>'Reward not found',
				]);
			}
			if ($reward->quantity <= 0) {
				return Response::json([
					'success'=>false,
					'message'=>'Reward not found',
				]);
			}
			$check = UserReward::where('reward_id', $reward->id)
				->where('user_id', $input['user_id'])->first();
			if ($check != null)
				return Response::json([
					'success'=>false,
					'message'=>'User already redeem this reward',
				]);

			$userReward = new UserReward();
			$userReward->user_id = $input['user_id'];
			$userReward->reward_id = $input['reward_id'];
			$userReward->merchant_id = $reward->merchant_id;
			$userReward->save();

			// Call queue push notify to user app

			return Response::json([
				'success'=>true,
			    'message'=>'Redeem successfully',
			]);

		} else {
			return Response::json([
				'success'=>false,
			    'message'=>$this->resolveFailMessage($validator->messages()),
			]);
		}
	}
}

?>