<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassbookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passbook_registrations',function(Blueprint $table){
            $table->increments('id');
            $table->integer('passbook_id');
            $table->integer('passbook_device_id');
            $table->timestamps();
        });

        Schema::create('passbooks',function(Blueprint $table){
            $table->increments('id');
            $table->char('pass_type_id');
            $table->char('serial_number');
            $table->char('last_updated_tag')->nullable();
            $table->timestamps();
        });
        Schema::create('passbook_devices',function(Blueprint $table){
            $table->increments('id');
            $table->char('device_library_identifier');
            $table->char('push_token');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
