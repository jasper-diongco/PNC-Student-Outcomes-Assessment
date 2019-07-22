<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerformanceCriteriaIndicatorArchive extends Model
{
    public $guarded = [];

    public function performanceIndicator() {
      return $this->belongsTo('App\PerformanceIndicator');
    }
}
