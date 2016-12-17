<?php

namespace App\Http\Controllers;

use App\Models\Passbook;
use App\Models\PassbookDevice;
use DB;
use Request;

class PassbookController extends Controller {

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

}