<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageObject extends Model
{
    public $guarded = [];


    public function getHtml() {
        return '<img src=' 
                . '"' . url('/storage/' . $this->path) . '" ' .
                'width=' . '"' . $this->width . '" ' .
                'height=' . '"' . $this->height . '" ' .
                 '/>';
    }
}
