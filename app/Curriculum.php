<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    public $fillable = ['program_id', 'name', 'description', 'year', 'user_id', 'year_level'];

    public function program() {
        return $this->belongsTo('App\Program');
    }

    public function curriculumCourses() {
        return $this->hasMany('App\CurriculumCourse');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
