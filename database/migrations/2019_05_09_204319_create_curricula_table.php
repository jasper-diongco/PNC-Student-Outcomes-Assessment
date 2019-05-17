<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id');
            $table->string('name', 255);
            $table->string('description', 255)->nullable();
            $table->string('year');
            $table->integer('user_id');//the one who created
            $table->boolean('is_saved')->default(false);
            $table->integer('year_level');
            $table->integer('revision_no');
            $table->integer('ref_id');
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
        Schema::dropIfExists('curricula');
    }
}
