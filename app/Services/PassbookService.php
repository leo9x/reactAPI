<?php namespace App\Services;

use App\Models\Passbook;
use Carbon\Carbon;
use Common;
use CustomException;
use DB;
use Logger;
use NotFoundException;
use Page;
use Storage;
use User;

class PassbookService
{

    //point change, member tier change
    public function pushNotificationsByUser(User $user, $report = false)
    {
        if ($report) {
            Logger::logPassbook('Attempt to push by user', ['user_id' => $user->getKey()]);
        }

        $qr_code = $user->userQrCodes()->first();

        $pass_type_id = 'pass.com.igift';

        if ( ! empty($qr_code)) {
            $this->pushNotifications($qr_code->code, $pass_type_id);
        }
    }

    public function pushNotificationsByPassbook(Passbook $passbook)
    {
        //passbook available
        $push_tokens = $passbook->passbookDevices()->lists('push_token');
        if (empty($push_tokens)) {
            return false;
        }

        $responses = $this->processPushTokens($push_tokens,false);

        return $responses;
    }

    public function pushNotifications($serial_number, $pass_type_id)
    {
        $passbook = Passbook::where('serial_number', $serial_number)->where('pass_type_id', $pass_type_id)->first();
        if (empty($passbook)) {
            //NOTE: DO NOT THROW EXCEPTION HERE
            return false;
        }

        $responses = $this->pushNotificationsByPassbook($passbook);

        return $responses;
    }

    public function processPushTokens($push_tokens, $queue = true, $report = false)
    {


        $responses = [];

        if ($queue) {
            $this->pushTokensByQueue($push_tokens);
        } else {
            $responses = $this->pushTokens($push_tokens);
        }

        return $responses;
    }

    public function pushTokensByQueue($push_tokens)
    {
        \Queue::push('PassbookService', ['push_tokens' => $push_tokens]);
    }

    public function pushTokens($push_tokens)
    {

        $errors = [];

        $apnsHost = 'gateway.push.apple.com';
        $apnsPort = 2195;
        $apnsCert = Storage::get('app/config/passbook/certificates.pem');
        $payload = ['aps' => []];
        $payload = json_encode($payload);

        $streamContext = stream_context_create();
        stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
        stream_context_set_option($streamContext, 'ssl', 'passphrase', 'okia77610889');

        $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
        stream_set_blocking ($apns, 0);

        if( ! $apns) {
            return "Failed to connect (stream_socket_client): $error $errorString";
        } else {
            foreach($push_tokens as $idx => $push_token) {
                $msg = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $push_token)) . chr(0) . chr(mb_strlen($payload)) . $payload;

                $success = fwrite($apns, $msg);
                if ($success === strlen($msg)) { // log success
                    //Logger::logPassbook('Push success', ['push_token' => $push_token]);
                } else {
                    //Logger::logPassbook('Push failed', ['push_token' => $push_token]);
                }
            }
        }

        @socket_close($apns);
        fclose($apns);

        return $errors;
    }
}