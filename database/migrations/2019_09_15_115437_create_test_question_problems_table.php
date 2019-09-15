<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestQuestionProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_question_problems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            $table->integer('test_question_id');
            $table->text('message');
            $table->boolean('is_resolved')->default(false);
            $table->text('resolved_description')->nullable();
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
        Schema::dropIfExists('test_question_problems');
    }
}
