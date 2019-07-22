<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentOutcomeArchive extends Model
{
    public $guarded = [];

    public function performanceCriteriaArchives() {
      return $this->hasMany('App\PerformanceCriteriaArchive');
    }

    public function program() {
        return $this->belongsTo('App\Program');
    }
}
