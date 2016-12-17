<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassbookDevice extends Model {

    public function passbooks()
    {
        return $this->belongsToMany('Passbook', 'passbook_registrations', 'passbook_device_id', 'passbook_id')
            ->withTimestamps();
    }
}