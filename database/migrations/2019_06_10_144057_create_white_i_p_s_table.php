<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhiteIPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('white_ip', function (Blueprint $table)
        {
            $table->increments('id')->unique()->comment('流水號');
            $table->string('ip', 255)->comment('白名單IP');
        });
    }

}
