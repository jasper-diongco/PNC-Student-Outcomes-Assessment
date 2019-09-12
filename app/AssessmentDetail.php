<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentDetail extends Model
{
    public $guarded = [];

    public function testQuestion() {
        return $this->belongsTo('App\TestQuestion');
    }
}
