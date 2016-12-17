<?php
namespace App\Http\Controllers;

use App\Models\Merchant;
use Hash;
use Request;
use Response;
use Validator;

class MerchantController extends Controller {


        public function details(){
            $merchant = Request::get('merchant_token');
            $data = new \stdClass();
            $data->name = $merchant->name;
            $data->logo = $merchant->logo;
            $data->color = trim(preg_replace('/\s\s+/', ' ', $merchant->color));
            $data->latitude = $merchant->latitude;
            $data->longtitude = $merchant->longtitude;
            return Response::json($data,200);
        }

        public function login(){
            $data = Request::all();
            $rules = array(
                'email'=>'required',
                'password'=>'required'
            );

            $validator = Validator::make($data, $rules);
            if (!$validator->fails()) {
                $merchant = Merchant::where('email',$data['email'])->first();
                if(count($merchant) == 0){
                    $return = array();
                    $return['success'] = FALSE;
                    $return['message'] = array('email'=>'Email not exists');
                    return Response::json($return,200);
                }
                if (!Hash::check($data['password'], $merchant->password))
                {
                    $return = array();
                    $return['success'] = FALSE;
                    $return['message'] = array('email'=>'Password not correct');
                    return Response::json($return,200);
                }

                $return = array();
                $return['success'] = TRUE;
                $data = new \stdClass();
                $data->name = $merchant->name;
                $data->logo = $merchant->logo;
                $data->color = trim(preg_replace('/\s\s+/', ' ', $merchant->color));
                $data->latitude = $merchant->latitude;
                $data->longtitude = $merchant->longtitude;
                $data->merchant_key = $merchant->merchant_key;
                $return['data'] = $data;
                return Response::json($return,200);
            }
            else {
                $return = array();
                $return['success'] = FALSE;
                $return['message'] = $validator->messages();
                return Response::json($return,200);
            }

        }
}

?>