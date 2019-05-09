<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    public $fillable = ['program_id', 'name', 'description', 'year', 'user_id'];

    public function program() {
        return $this->belongsTo('App\Program');
    }
}
