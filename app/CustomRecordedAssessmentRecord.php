<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomRecordedAssessmentRecord extends Model
{
    public $guarded = [];

    public function student() {
        return $this->belongsTo('App\Student')->with('user');
    }
}
