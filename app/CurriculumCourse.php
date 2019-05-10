<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurriculumCourse extends Model
{
    public $fillable = ['curriculum_id', 'course_id', 'year_level', 'semester'];

    public function curriculum() {
      return $this->belongsTo('App\Curriculum');
    }

    public function course() {
      return $this->belongsTo('App\Course');
    }
}
