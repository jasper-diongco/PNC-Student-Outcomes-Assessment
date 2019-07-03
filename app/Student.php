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

    public function curriculum() {
        return $this->belongsTo('App\Curriculum');
    }

    public function grades() {
        return $this->hasMany('App\Grade')->with('gradeValue');
    }

    public function getGradesByCourse($course_id) {
        return Grade::where('course_id', $course_id)
            ->where('student_id', $this->id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
