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
      return $this->hasMany('App\StudentOutcome')->orderBy('so_code', 'ASC');
    }
}
