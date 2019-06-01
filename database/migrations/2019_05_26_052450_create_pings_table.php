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
            $table->increments('id', 10)->unique()->comment('項目ID');
            $table->char('name', 4)->comment('項目名稱');
            $table->smallInteger('numerical_value')->comment('坪數價錢和預算％數');
        });
    }
}
