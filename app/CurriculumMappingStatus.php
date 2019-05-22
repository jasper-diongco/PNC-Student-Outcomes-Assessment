<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurriculumMappingStatus extends Model
{
    public $fillable = ['curriculum_id', 'status'];
}
