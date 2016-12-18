<?php

namespace App\Http\Controllers;

use App\Library\PassGenerator;
use App\Models\Passbook;
use App\Models\PassbookDevice;
use App\Services\PassbookService;
use DB;
use Input;
use Monolog\Logger;
use Request;
use Response;

class PassbookController extends Controller {

    private $passbook_service;
    public function __construct(){
        $this->passbook_service = new PassbookService();
    }

    public function register($version, $deviceLibraryIdentifier, $passTypeIdentifier, $serialNumber){

        $data = Request::all();

        DB::beginTransaction();

        try {
            if (Request::has('pushToken')) {
                $passbook = Passbook::where('pass_type_id', $passTypeIdentifier)
                    ->where('serial_number', $serialNumber)
                    ->first();

                if (empty($passbook)) {
                    $passbook = new Passbook();
                    $passbook->forceFill([
                        'pass_type_id' => $passTypeIdentifier,
                        'serial_number' => $serialNumber,
                    ]);

                    $passbook->save();
                }

                $passbook_device = PassbookDevice::where('push_token', $data['pushToken'])->first();

                if (empty($passbook_device)) {
                    $passbook_device = new PassbookDevice();
                    $passbook_device->forceFill([
                        'device_library_identifier' => $deviceLibraryIdentifier,
                        'push_token' => $data['pushToken']
                    ]);

                    $passbook_device->save();
                } else {
                    $passbook_device->device_library_identifier = $deviceLibraryIdentifier;
                    $passbook_device->save();
                }

                $existed = (bool) $passbook->passbookDevices()->where('passbook_registrations.passbook_device_id', $passbook_device->getKey())->count();

                if ( ! $existed) {
                    $passbook->passbookDevices()->save($passbook_device);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function listSerialNumbers($version, $deviceLibraryIdentifier, $passTypeIdentifier)
    {

        $passbook_device = PassbookDevice::where('device_library_identifier', $deviceLibraryIdentifier)->first();

        if ( ! empty($passbook_device)) {
            $passbook_serial_numbers = $passbook_device->passbooks()
                ->where('passbooks.pass_type_id', $passTypeIdentifier)
                ->lists('serial_number');
        }

        $rs = [
            "lastUpdated" => (string)(time() - (24*60*60)),
            "serialNumbers" => [
                "d568866a45dfc010ac680a9d06e5a48feb9216a2"
            ]
        ];

        return Response::json($rs, 200);
    }

    public function test($id_or_code){
        $pkpass = Passbook::getPkpassData($id_or_code, 1, true);
        print_r($pkpass);
        die;
        return new Response($pkpass, 200, [
            'Content-Transfer-Encoding' => 'binary',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="pass.pkpass"',
            'Content-length' => strlen($pkpass),
            'Content-Type' => PassGenerator::getPassMimeType(),
            'Pragma' => 'no-cache',
            'Last-Modified' => gmdate('D, d M Y H:i:s T')
        ]);
    }

    public function getPassData($version, $passTypeIdentifier, $serialNumber)
    {
        Passbook::getPassData($version, $passTypeIdentifier, $serialNumber);
        $business = Session::get('business');
        $pkpass = Passbook::getPkpassData($serialNumber, $business['id'], true);

        return new Response($pkpass, 200, [
            'Content-Transfer-Encoding' => 'binary',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="pass.pkpass"',
            'Content-length' => strlen($pkpass),
            'Content-Type' => PassGenerator::getPassMimeType(),
            'Pragma' => 'no-cache',
            'Last-Modified' => gmdate('D, d M Y H:i:s T')
        ]);
    }

//    public function getPassData($version, $passTypeIdentifier, $serialNumber)
//    {
//        return Response::json([], 200);
//    }

    public function logPassbookError($version)
    {
        Logger::logError(1, "Passbook Error", []);
        return Response::json([], 200);
    }

    public function pushNotification()
    {
        $serialNumber = Input::get('serial_number');
        $passIdentify = Input::get('pass_type_id');

        $errors = $this->passbook_service->pushNotifications($serialNumber, $passIdentify);

        return Response::json($errors);
    }

    public function unRegister($version, $deviceLibraryIdentifier, $passTypeIdentifier, $serialNumber)
    {
        $passbook = Passbook::where('pass_type_id', $passTypeIdentifier)
            ->where('serial_number', $serialNumber)
            ->first();

        if ( ! empty($passbook)) {
            $passbook_device = PassbookDevice::where('device_library_identifier', $deviceLibraryIdentifier)
                ->first();

            if ( ! empty($passbook_device)) {
                $passbook->passbookDevices()->detach($passbook_device->getKey());
            }
        }
    }

}