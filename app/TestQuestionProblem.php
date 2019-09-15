<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestQuestionProblem extends Model
{
    public $guarded = [];

    public function testQuestion() {
        return $this->belongsTo('App\TestQuestion');
    }

    public function student() {
        return $this->belongsTo('App\Student')->with('user');
    }
}
