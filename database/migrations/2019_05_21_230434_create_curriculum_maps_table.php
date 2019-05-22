<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculumMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_maps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_outcome_id');
            $table->integer('curriculum_course_id');
            $table->boolean('is_checked');
            $table->integer('learning_level_id');
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
        Schema::dropIfExists('curriculum_maps');
    }
}
