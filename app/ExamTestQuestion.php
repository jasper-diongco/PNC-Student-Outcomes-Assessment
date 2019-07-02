<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamTestQuestion extends Model
{
    public $guarded = [];

    public function testQuestion() {
        return $this->belongsTo('App\TestQuestion');
    }
}
