<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurriculumMap extends Model
{
    public $fillable = ['student_outcome_id', 'curriculum_course_id', 'is_checked', 'learning_level_id'];


    public function curriculumCourse() {
        return $this->belongsTo('App\CurriculumCourse');
    }
}
