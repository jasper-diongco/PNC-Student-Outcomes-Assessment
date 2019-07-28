<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("exam_id");
            $table->integer("student_id");
            $table->integer("student_outcome_id");
            $table->integer("curriculum_id");
            $table->double("time_limit");
            $table->text("description");
            $table->double("passing_grade");
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
        Schema::dropIfExists('answer_sheets');
    }
}
