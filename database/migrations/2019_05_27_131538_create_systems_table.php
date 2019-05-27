<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system', function (Blueprint $table) {
            $table->increments('system_id')->unique()->comment('系統大項目ID');
            $table->string('system_name', 255)->comment('系統大項目名稱');
            $table->unsignedInteger('sort')->comment('排序');
        });
    }

}
