<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantTale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
			$table->increments('id');
	        $table->string('email')->unique();
	        $table->string('password');
            $table->string('name');
            $table->text('logo')->nullable();
            $table->string('color')->nullable();
	        $table->float('lat', 30)->default(0);
	        $table->float('long', 30)->default(0);
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
