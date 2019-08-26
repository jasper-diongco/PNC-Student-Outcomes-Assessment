<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAnalysisDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_analysis_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_analysis_id');
            $table->integer('test_question_id');
            $table->boolean('is_resolved');
            $table->string('action_resolved')->default('');
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
        Schema::dropIfExists('item_analysis_details');
    }
}
