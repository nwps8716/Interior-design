<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_system', function (Blueprint $table) {
            $table->increments('sub_system_id')->unique()->comment('系統子項目ID');
            $table->string('sub_system_name', 255)->comment('系統子項目內容物');
            $table->string('format', 255)->comment('系統子項目規格');
            $table->mediumInteger('unti_price')->comment('系統子項目單價');
            $table->string('unti', 32)->comment('系統子項目單位');
            $table->unsignedInteger('system_id')->comment('系統大項目ID');
        });
    }

}