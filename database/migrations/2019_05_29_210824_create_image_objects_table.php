<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_objects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('path');
            $table->integer('width');
            $table->integer('height');
            $table->integer('size');
            $table->integer('test_question_id');
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
        Schema::dropIfExists('image_objects');
    }
}
