<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MathObject extends Model
{
    public $guarded = [];

    public function getHtml() {
        return '<span>$$' . $this->formula . '$$</span>';
    }
}
