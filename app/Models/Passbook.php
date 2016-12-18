<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\PassGenerator;

class Passbook extends Model {

    public function passbookDevices()
    {
        return $this->belongsToMany('App\Models\PassbookDevice', 'passbook_registrations', 'passbook_id', 'passbook_device_id')
            ->withTimestamps();
    }

    public static function getPkpassData($user_id_or_hash, $merchant_id)
    {
        $user = User::getUserByCode($user_id_or_hash);

        $merchant = Merchant::find($merchant_id)->toArray();
        $merchant_bg = ($merchant['background'] != '')?env('APP_URL').$merchant['background']:'';

        $merchant_logo = ($merchant['logo'] != '')?env('APP_URL').$merchant['logo']:'';

        $points = $user['point'];
        $point_title = 'Points';

        $friendly_expiry_date = (!empty($user['member_tiers']) && !empty($user['member_tiers'][0]['pivot'])) ? $user['member_tiers'][0]['pivot']['friendly_expiry_date'] : '';
        $data['friendly_expiry_date'] = $friendly_expiry_date;


        $hash = $user["qr_code"] . '-' . $merchant_id;
        $identify = $hash . '-' . $merchant_id;

        $merchant_name = $merchant['name'];
        $merchant_color = $merchant['color'];

        $locations = [];

        $pass = new PassGenerator($hash, true);
        $pass_definition = [
            "description"       => $merchant_name . "'s member store card",
            "formatVersion"     => 1,
            "organizationName"  => $merchant_name,
            "passTypeIdentifier"=> "pass.com.igift",
            "serialNumber"      => $identify,
            "teamIdentifier"    => "L93FTHRFUZ",
            "foregroundColor"   => "#ffffff",
            "backgroundColor"   => $merchant_color,
            "labelColor" => "#ffffff",
            "barcode" => [
                "message"   => $hash,
                "messageEncoding"=> "utf-8",
                "format" => "PKBarcodeFormatQR"
            ],
            'logoText' => strlen($merchant_name) <= 15 ? $merchant_name : '',
            "storeCard" => [
                "headerFields" => [
                    [
                        "key" => "points",
                        "label" => $point_title,
                        "value" => $points,
                        "changeMessage" => 'Your points just changed to %@'
                    ]
                ],
                "primaryFields" => [

                ],
                "secondaryFields" => [
                    [
                        "key" => "name",
                        "label" => "",
                        "value" => $user['name']
                    ]
                ],
                "auxiliaryFields" => [

                ],
                "backFields" => [
                    [
                        "key" => "phone",
                        "label" => "Phone",
                        "value" => $user['phone']
                    ],
                    [
                        "key" => "email",
                        "label" => "Email",
                        "value" => $user['email']
                    ]
                ]
            ],
            "locations" => $locations,
            'authenticationToken' => '05e69073619db4c555c7001aeafc8c42c1d570f1',
            'webServiceURL' => url('/passbook')
        ];

        $pass->setPassDefinition($pass_definition);

// Definitions can also be set from a JSON string
// $pass->setPassDefinition(file_get_contents('/path/to/pass.json));

// Add assets to the PKPass package
//        $pass->addAsset('https://d30nlu27opq44x.cloudfront.net/resized/uVXdjQ9ypScOQC2q60IJ8sIctM7vEjwZ6F2oz1Yv_600.jpg');
        $pass->addAsset(base_path() . '/public/image/wallet/icon.png');
        $pass->addAsset(base_path() . '/public/image/wallet/icon@2x.png');
        $pass->addAsset(base_path() . '/public/image/wallet/icon@3x.png');
        if($merchant_bg) {
            $pass->addAssetViaUrl('strip.png', $merchant_bg);
        }

        if($merchant_logo) {
            $pass->addAssetViaUrl('logo.png', $merchant_logo);
        }

        $pkpass = $pass->create();

        return $pkpass;
    }
}