<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerSheetTestQuestionChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_sheet_test_question_choices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('answer_sheet_test_question_id');
            $table->integer('test_question_id');
            $table->text('body');
            $table->text('body_html');
            $table->boolean('is_correct');
            $table->boolean('is_selected')->default(false);
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
        Schema::dropIfExists('answer_sheet_test_question_choices');
    }
}
