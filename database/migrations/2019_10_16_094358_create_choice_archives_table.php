<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoiceArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choice_archives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ch_code');
            $table->integer('test_question_id');
            $table->text('body');
            $table->boolean('is_correct');
            $table->boolean('is_active');
            $table->integer('user_id');
            $table->integer('pos_order');
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
        Schema::dropIfExists('choice_archives');
    }
}
