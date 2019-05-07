<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    public $timestamps = false; 

    protected $fillable = ['college_code', 'name', 'faculty_id'];


    public function faculty() {
      return $this->belongsTo('App\Faculty');
    }
}
