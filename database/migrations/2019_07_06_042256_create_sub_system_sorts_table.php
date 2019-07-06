<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubSystemSortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_system_sort', function (Blueprint $table) {
            $table->increments('sgn_id')->unique()->comment('流水號');
            $table->unsignedInteger('system_id')->comment('系統大項目ID');
            $table->string('general_name', 255)->comment('統稱')->nullable();
            $table->unsignedInteger('sort')->comment('排序');
        });
    }

}
