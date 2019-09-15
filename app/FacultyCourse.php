<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacultyCourse extends Model
{
    public $guarded = [];

    public function course() {
        return $this->belongsTo('App\Course');
    }

    public function getFacultyCourseTestQuestions() {

        $faculty = Faculty::find($this->faculty_id); 

        return TestQuestion::where('user_id', $faculty->user->id)
                        ->where('course_id', $this->course_id)
                        ->with('course')
                        ->with('choices')
                        ->with('user')
                        ->latest()
                        ->get();
    }
}
