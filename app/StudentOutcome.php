<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentOutcome extends Model
{
    public $fillable = ['program_id', 'so_code', 'description'];

    public function performanceCriterias() {
      return $this->hasMany('App\PerformanceCriteria');
    }

    public function curriculumMaps() {
        return $this->hasMany('App\CurriculumMap')->where('is_checked', true);
    }

    public function program() {
        return $this->belongsTo('App\Program');
    }
}
