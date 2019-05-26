<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pings', function (Blueprint $table) {
            $table->tinyInteger('id')->unique()->comment('項目ID');
            $table->char('name', 4)->comment('項目名稱');
            $table->tinyInteger('percent')->comment('%數');
        });
    }
}
