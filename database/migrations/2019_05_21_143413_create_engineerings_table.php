<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngineeringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engineering', function (Blueprint $table) {
            $table->increments('project_id')->unique()->comment('工程大項目ID');
            $table->string('project_name', 255)->comment('工程大項目名稱');
            $table->unsignedInteger('sort')->comment('排序');
        });
    }
}
