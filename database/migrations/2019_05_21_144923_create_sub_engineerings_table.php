<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubEngineeringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_engineering', function (Blueprint $table) {
            $table->increments('sub_project_id')->unique()->comment('工程子項目ID');
            $table->string('sub_project_name', 255)->comment('工程子項目名稱');
            $table->mediumInteger('unit_price')->comment('工程子項目單價');
            $table->string('unit', 32)->comment('工程子項目單位');
            $table->unsignedInteger('project_id')->comment('工程大項目ID');
            $table->string('remark', 255)->comment('備註')->default('');
        });
    }
}
