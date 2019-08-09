<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerSheetTestQuestionChoice extends Model
{
    public $guarded = [];

    public function choice() {
        return $this->belongsTo('App\Choice');
    }
}
