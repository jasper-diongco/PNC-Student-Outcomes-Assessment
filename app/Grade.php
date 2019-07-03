<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public $guarded = [];
    //student_id,course_id,grade_value_id,is_passed

    public function gradeValue() {
        return $this->belongsTo('App\GradeValue');
    }

    public function faculty() {
        return $this->belongsTo('App\Faculty');
    }


}
