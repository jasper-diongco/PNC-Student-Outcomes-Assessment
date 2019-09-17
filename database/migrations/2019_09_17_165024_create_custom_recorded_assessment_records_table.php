<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomRecordedAssessmentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_recorded_assessment_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->integer('custom_recorded_assessment_id');
            $table->integer('student_id');
            $table->double('score');
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
        Schema::dropIfExists('custom_recorded_assessment_records');
    }
}
