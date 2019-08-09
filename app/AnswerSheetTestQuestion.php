<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerSheetTestQuestion extends Model
{
    public $guarded = [];

    public function answerSheetTestQuestionChoices() {
        return $this->hasMany('App\AnswerSheetTestQuestionChoice')->orderBy('pos_order', 'ASC');
    }

    public function testQuestion() {
        return $this->belongsTo('App\TestQuestion');
    }

    public function getAnswer() {
        $choices = $this->answerSheetTestQuestionChoices;
        $answer = null;

        foreach ($choices as $choice) {
            if($choice->is_selected) {
                $answer = $choice;
                break;
            }
        }

        return $answer;
    }

    public function getCorrectAnswer() {
        $choices = $this->answerSheetTestQuestionChoices;
        $correct_answer = null;

        foreach ($choices as $choice) {
            if($choice->is_correct) {
                $correct_answer = $choice;
                break;
            }
        }

        return $correct_answer;
    }

    public function checkIfCorrect() {
        $choices = $this->answerSheetTestQuestionChoices;
        $is_correct = false;

        foreach ($choices as $choice) {
            if($choice->is_selected && $choice->is_correct) {
                $is_correct = true;
                break;
            }
        }

        return $is_correct;
    }


    // public function getAnswerSheetTestQuestionChoicesOrig() {
    //     return $this->hasMany('App\AnswerSheetTestQuestionChoice')->orderBy('pos_order', 'ASC');
    // }
}
