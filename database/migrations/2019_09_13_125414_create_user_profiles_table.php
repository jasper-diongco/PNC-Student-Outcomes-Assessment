<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('house_no');
            $table->string('barangay');
            $table->string('town_city');
            $table->string('province');
            $table->string('country');
            $table->string('zip_code');
            $table->string('place_of_birth');
            $table->string('civil_status');
            $table->string('nationality');
            $table->string('religion');
            $table->string('contact_no');
            $table->timestamps();
        });
    }

    /*
    house_no: '',
    barangay: '',
    town_city: '',
    province: '',
    country: 'Philippines',
    zip_code: '',
    place_of_birth: '',
    civil_status: '',
    nationality: '',
    religion: '',
    contact_no: ''*/

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
