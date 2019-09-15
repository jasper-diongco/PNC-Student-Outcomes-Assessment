<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['course_code', 'description', 'lec_unit', 'lab_unit', 'college_id', 'is_public', 'color'];

    public function college() {
      return $this->belongsTo('App\College');
    }

    public function testQuestions() {
        return $this->hasMany('App\TestQuestion')->where('is_active', true);
    }
}
