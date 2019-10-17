<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestQuestionArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_question_archives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('type_id');
            $table->string('tq_code');
            $table->text('body');
            $table->integer('student_outcome_id');
            $table->integer('course_id');
            $table->integer('difficulty_level_id');
            $table->integer('user_id');
            $table->boolean('is_active')->default(true);
            $table->integer('performance_criteria_id');
            $table->string('ref_id');
            $table->integer('parent_id')->nullable();
            $table->integer('version_no')->nullable();
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
        Schema::dropIfExists('test_question_archives');
    }
}
