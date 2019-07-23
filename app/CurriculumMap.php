<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurriculumMap extends Model
{
    public $fillable = ['student_outcome_id', 'curriculum_course_id', 'is_checked', 'learning_level_id'];


    public function curriculumCourse() {
        return $this->belongsTo('App\CurriculumCourse');
    }

    public function testQuestionCount() {
        return TestQuestion::where('student_outcome_id', $this->student_outcome_id)
            ->where('course_id', $this->curriculumCourse->course->id)
            ->where('is_active', true)
            ->count();
    }

    public function learningLevel() {
        return $this->belongsTo('App\LearningLevel');
    }
}
