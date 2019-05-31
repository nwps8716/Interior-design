<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_budget', function (Blueprint $table)
        {
            $table->bigIncrements('id')->unique()->comment('流水號');
            $table->string('user_name', 255)->comment('使用者名稱');
            $table->tinyInteger('category_id')->comment('工程:1，系統:2，好禮贈送:3');
            $table->smallInteger('level_id')->comment('工程級距ID');
            $table->smallInteger('sub_project_id')->comment('工程子項目ID');
            $table->smallInteger('sub_project_number')->comment('工程子項目數量');
            $table->string('remark', 255)->comment('備註');
        });
    }

}
