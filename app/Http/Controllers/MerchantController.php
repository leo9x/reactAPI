<?php
namespace App\Http\Controllers;

use Request;
use Response;

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
}

?>