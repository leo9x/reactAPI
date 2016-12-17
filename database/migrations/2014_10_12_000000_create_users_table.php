<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->nullable();
	        $table->string('phone')->nullable();
            $table->string('password');
	        $table->integer('point')->default(0);
	        $table->string('user_token')->unique();
	        $table->string('qr_code')->unique();
	        $table->string('avatar')->default('http://2.pik.vn/201658e4e26c-c66c-4590-bd12-99f80e031eca.png');
            $table->rememberToken();
	        $table->softDeletes();
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
        Schema::drop('users');
    }
}
