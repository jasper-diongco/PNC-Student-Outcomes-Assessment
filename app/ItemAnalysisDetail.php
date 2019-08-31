<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAnalysisDetail extends Model
{
    public $guarded = [];

    public function testQuestion() {
        return $this->belongsTo('App\TestQuestion');
    }
}
