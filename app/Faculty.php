<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = ['user_id','college_id', 'is_active'];


    public function user() {
      return $this->belongsTo('App\User');
    }

    public function college() {
      return $this->belongsTo('App\College');
    }
}
