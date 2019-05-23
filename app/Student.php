<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    public $fillable = ['student_id', 'program_id', 'curriculum_id', 'user_id'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function program() {
        return $this->belongsTo('App\Program');
    }
}
