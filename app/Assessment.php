<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    public $guarded = [];


    public function assessmentDetails() {
        return $this->hasMany('App\AssessmentDetail');
    }
}
