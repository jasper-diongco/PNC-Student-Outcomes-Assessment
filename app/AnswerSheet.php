<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerSheet extends Model
{
    public $guarded = [];

    public function answerSheetTestQuestions() {
        return $this->hasMany('App\AnswerSheetTestQuestion')->with('answerSheetTestQuestionChoices');
    }

    public function exam() {
        return $this->belongsTo('App\Exam');
    }
}
