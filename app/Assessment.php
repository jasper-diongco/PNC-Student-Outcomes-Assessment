<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Assessment extends Model
{
    public $guarded = [];

    public function student() {
        return $this->belongsTo('App\Student')->with('user');
    }

    public function studentOutcome() {
        return $this->belongsTo('App\StudentOutcome')->with('program');
    }

    public function exam() {
        return $this->belongsTo('App\Exam');
    }

    public function assessmentDetails() {
        return $this->hasMany('App\AssessmentDetail')->with('testQuestion');
    }


    public function getCorrectAnswers() {
        return AssessmentDetail::where('is_correct', true)
                    ->where('assessment_id', $this->id)
                    ->get();
    }

    public function countCorrectAnswers() {
        return AssessmentDetail::where('is_correct', true)
                    ->where('assessment_id', $this->id)
                    ->count();
    }

    public function getIncorrectAnswers() {
        return AssessmentDetail::where('is_correct', false)
                ->where('assessment_id', $this->id)
                ->get();
    }

    public function getAnsweredTestQuestions() {
        return AssessmentDetail::where('choice_id', '!=', null)
                ->where('assessment_id', $this->id)
                ->get();
    }

    public function getUnansweredTestQuestions() {
        return AssessmentDetail::where('choice_id', '=', null)
            ->where('assessment_id', $this->id)
            ->get();
    }

    public function computeScore() {
        $score = $this->getCorrectAnswers()->count();

        $total_items = $this->assessmentDetails->count();

        return ($score / $total_items) * 100;
    }

    public function checkIfPassed() {
        $answer_sheet = AnswerSheet::where('student_id', $this->student_id)
                                ->where('student_outcome_id', $this->student_outcome_id)
                                ->where('exam_id', $this->exam_id)
                                ->first();
        $score = $this->computeScore();

        if($score >= $answer_sheet->passing_grade) {
            return true;
        } else {
            return false;
        }
    }

    public function getDuration() {
        $answer_sheet = AnswerSheet::where('student_id', $this->student_id)
                                ->where('student_outcome_id', $this->student_outcome_id)
                                ->where('exam_id', $this->exam_id)
                                ->first();
        $totalDuration = $this->time_consumed;

        if($totalDuration > $answer_sheet->time_limit * 60) {
            $totalDuration = $answer_sheet->time_limit * 60;
        }


        return gmdate('H:i:s', $totalDuration);
    }

    public function getScoreLabel() {
        $performance_criterias = $this->studentOutcome->performanceCriterias;

        if($performance_criterias->count() <= 0) {
            return '';
        }

        $performance_indicators = $performance_criterias[0]->performanceCriteriaIndicators;

        $score = $this->computeScore();

        // foreach ($performance_indicators as $performance_indicator) {
        //     if($score >= $performance_indicator->score_percentage) {
        //         return $performance_indicator->description;
        //     }
        // }

        if($score < $performance_indicators[0]->score_percentage) {
            return $performance_indicators[0]->performanceIndicator->description;
        }

        for($i = 0; $i < count($performance_indicators) - 1; $i++) {
            if($score >= $performance_indicators[$i]->score_percentage && $score < $performance_indicators[$i + 1]->score_percentage) {
                return $performance_indicators[$i]->performanceIndicator->description;
            }
        }

        return  $performance_indicators[count($performance_indicators) - 1]->performanceIndicator->description;
    }

    public function getScoreDescription() {
        $performance_criterias = $this->studentOutcome->performanceCriterias;

        if($performance_criterias->count() <= 0) {
            return '';
        }

        $performance_indicators = $performance_criterias[0]->performanceCriteriaIndicators;

        $score = $this->computeScore();

        // foreach ($performance_indicators as $performance_indicator) {
        //     if($score >= $performance_indicator->score_percentage) {
        //         return $performance_indicator->description;
        //     }
        // }

        if($score < $performance_indicators[0]->score_percentage) {
            return $performance_indicators[0]->description;
        }

        for($i = 0; $i < count($performance_indicators) - 1; $i++) {
            if($score >= $performance_indicators[$i]->score_percentage && $score < $performance_indicators[$i + 1]->score_percentage) {
                return $performance_indicators[$i]->description;
            }
        }

        return  $performance_indicators[count($performance_indicators) - 1]->description;

    }

    public function getPerformanceCriteriaText() {
        $performance_criterias = $this->studentOutcome->performanceCriterias;

        if($performance_criterias->count() <= 0) {
            return '';
        }

        return $performance_criterias[0]->description;
    }



    

}
