<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerformanceCriteriaArchive extends Model
{
    public $guarded = [];

    public function performanceCriteriaIndicatorArchives() {
      return $this->hasMany('App\PerformanceCriteriaIndicatorArchive');
    }
}
