<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    public $fillable = ['title', 'body', 'student_outcome_id', 'course_id', 'difficulty_level_id', 'user_id', 'is_active', 'performance_criteria_id'];


    public function choices() {
        return $this->hasMany('App\Choice');
    }

    public function difficultyLevel() {
        return $this->belongsTo('App\DifficultyLevel');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function course() {
        return $this->belongsTo('App\Course');
    }

    public function studentOutcome() {
        return $this->belongsTo('App\StudentOutcome');
    }
}
