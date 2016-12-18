<?php
namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Reward;
use Hash;
use Request;
use Response;
use Validator;

class MerchantController extends Controller
{

	public function getListReward()
	{
		$merchant         = Request::get('merchant_token');
		$rewards = Reward::with('merchant_data')
			->where('merchant_id', $merchant->id)
			->orderBy('merchant_id', 'DESC')
			->orderBy('id', 'ASC')->get();
		$data    = [];
		$count  = count($rewards);
		foreach ($rewards as $reward) {
			$data[] = $reward->getRewardInfo();
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
}

?>