<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnswerSheet extends Model
{
    public $guarded = [];

    public function answerSheetTestQuestions() {
        return $this->hasMany('App\AnswerSheetTestQuestion')->with('answerSheetTestQuestionChoices');
    }

    public function exam() {
        return $this->belongsTo('App\Exam');
    }

    public function student() {
        return $this->belongsTo('App\Student');
    }

    public function studentOutcome() {
        return $this->belongsTo('App\StudentOutcome');
    }

    public function checkIfHasAvailableTime() {
        $now = Carbon::now();
        $start_time = Carbon::parse($this->created_at);

        $totalDuration = $now->diffInSeconds($start_time);

        if($totalDuration < $this->time_limit * 60) {
            return true;
        } else {
            return false;
        }
    }
}
