<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestQuestionArchive extends Model
{
    public $guarded = [];


    public function choiceArchives() {
        return $this->hasMany('App\ChoiceArchive')->orderBy('pos_order', 'ASC');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
