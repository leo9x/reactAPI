<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passbook extends Model {

    public function passbookDevices()
    {
        return $this->belongsToMany('PassbookDevice', 'passbook_registrations', 'passbook_id', 'passbook_device_id')
            ->withTimestamps();
    }
}