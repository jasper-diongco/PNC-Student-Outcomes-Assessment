<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['program_code', 'description', 'college_id', 'color'];

    public function college() {
      return $this->belongsTo('App\College');
    }

    public function studentOutcomes() {
      return $this->hasMany('App\StudentOutcome')->orderBy('so_code', 'ASC')->where('is_active', true);
    }

    public function deactivated_student_outcomes() {
      return StudentOutcome::where('program_id', $this->id)->orderBy('so_code', 'ASC')->where('is_active', false)->get();
    }

    public function curricula() {
        return $this->hasMany('App\Curriculum')->latest();
    }

    public function learningLevels() {
      return $this->hasMany('App\LearningLevel');
    }
}
