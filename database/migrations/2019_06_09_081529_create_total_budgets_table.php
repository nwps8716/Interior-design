<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotalBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_budget', function (Blueprint $table)
        {
            $table->increments('id')->unique()->comment('流水號');
            $table->string('user_name', 255)->comment('使用者名稱');
            $table->mediumInteger('total_budget')->unsigned()->default(100000)->comment('總預算');
            $table->tinyInteger('engineering_budget')->default(50)->comment('工程預算％數');
            $table->tinyInteger('system_budget')->default(50)->comment('系統預算％數');
            $table->decimal('system_discount', 4, 2)->default(0.00)->comment('系統折數');
        });
    }

}
