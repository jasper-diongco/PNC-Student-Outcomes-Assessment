<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Faculty;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'middle_name','sex','date_of_birth', 'contact_no','address','email','password','user_type_id',
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function userType() {
        return $this->belongsTo('App\UserType');
    }

    public function getFullName() {
        $full_name = $this->last_name . ', ' . $this->first_name;
        $full_name .= ' ' . $this->middle_name ?? '';

        return $full_name;
    }

    public function getFaculty() {
        return Faculty::where('user_id', $this->id)->first();
    }

    public function getStudent() {
        return Student::where('user_id', $this->id)->first();
    }
}
