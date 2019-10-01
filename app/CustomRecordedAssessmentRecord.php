<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomRecordedAssessmentRecord extends Model
{
    public $guarded = [];

    public function student() {
        return $this->belongsTo('App\Student')->with('user');
    }

    public function customRecordedAssessment() {
        return $this->belongsTo('App\CustomRecordedAssessment');
    }

    public function computeScore() {
        return round((($this->score / $this->customRecordedAssessment->overall_score) * 100), 2);
    }

    public function checkIfPassed() {

        $score = $this->computeScore();


        if($score >= $this->customRecordedAssessment->passing_percentage) {
            return true;
        } else {
            return false;
        }
    }

    public function getScoreLabel() {
        $performance_criterias = $this->customRecordedAssessment->studentOutcome->performanceCriterias;

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
        $performance_criterias = $this->customRecordedAssessment->studentOutcome->performanceCriterias;

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
        $performance_criterias = $this->customRecordedAssessment->studentOutcome->performanceCriterias;

        if($performance_criterias->count() <= 0) {
            return '';
        }

        return $performance_criterias[0]->description;
    }
}
