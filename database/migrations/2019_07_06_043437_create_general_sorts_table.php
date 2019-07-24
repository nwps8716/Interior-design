<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_sort', function (Blueprint $table) {
            $table->unsignedInteger('sub_system_id')->unique()->comment('系統子項目ID');
            $table->unsignedInteger('sort')->comment('排序');
        });
    }

}
