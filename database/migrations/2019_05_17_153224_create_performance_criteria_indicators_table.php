<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerformanceCriteriaIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performance_criteria_indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('performance_criteria_id');
            $table->integer('performance_indicator_id');
            $table->text('description');
            $table->double('score_percentage', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_criteria_indicators');
    }
}
