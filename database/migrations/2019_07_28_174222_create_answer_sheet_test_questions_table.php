<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerSheetTestQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_sheet_test_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('answer_sheet_id');
            $table->integer('test_question_id');
            $table->string('title');
            $table->text('body');
            $table->text('body_html');
            $table->integer('student_outcome_id');
            $table->integer('course_id');
            $table->integer('difficulty_level_id');
            $table->integer('performance_criteria_id');
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
        Schema::dropIfExists('answer_sheet_test_questions');
    }
}
