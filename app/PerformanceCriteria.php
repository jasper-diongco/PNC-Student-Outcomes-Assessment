<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerformanceCriteria extends Model
{
    public $fillable = ['description', 'student_outcome_id'];


    public function performanceCriteriaIndicators() {
      return $this->hasMany('App\PerformanceCriteriaIndicator')->orderBy('score_percentage', 'ASC');
    }
}
