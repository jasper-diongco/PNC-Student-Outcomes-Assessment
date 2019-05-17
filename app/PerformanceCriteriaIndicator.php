<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerformanceCriteriaIndicator extends Model
{
    public $fillable = ['performance_criteria_id', 'performance_indicator_id', 'description', 'score_percentage'];
}
