<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerSheetTestQuestion extends Model
{
    public $guarded = [];

    public function answerSheetTestQuestionChoices() {
        return $this->hasMany('App\AnswerSheetTestQuestionChoice')->orderBy('pos_order', 'ASC');
    }

    // public function getAnswerSheetTestQuestionChoicesOrig() {
    //     return $this->hasMany('App\AnswerSheetTestQuestionChoice')->orderBy('pos_order', 'ASC');
    // }
}
