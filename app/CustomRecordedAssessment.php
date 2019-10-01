<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomRecordedAssessment extends Model
{
    public $guarded = [];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function studentOutcome() {
        return $this->belongsTo('App\StudentOutcome');
    }

    public function customRecordedAssessmentRecords() {
        return $this->hasMany('App\CustomRecordedAssessmentRecord')->with('student');
    }

}
