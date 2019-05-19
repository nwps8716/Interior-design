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
            $table->increments('user_id')->comment('使用者ID');
            $table->string('user_name', 32)->comment('使用者帳號');
            $table->string('password', 255)->comment('使用者密碼');
            $table->unsignedInteger('level')->default(3)->comment('使用者層級');
        });
    }

}
