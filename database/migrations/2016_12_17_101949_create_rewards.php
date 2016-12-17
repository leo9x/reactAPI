<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards',function(Blueprint $table){
            $table->increments('id');
            $table->integer('merchant_id');
            $table->string('name')->nullable();
            $table->text('logo')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('points');
            $table->timestamps();
            $table->softDeletes();
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
