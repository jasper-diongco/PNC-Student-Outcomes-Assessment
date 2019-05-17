<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerformanceCriteria extends Model
{
    public $fillable = ['description', 'student_outcome_id'];
}
