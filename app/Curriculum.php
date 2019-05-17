<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    public $fillable = ['program_id', 'name', 'description', 'year', 'user_id', 'year_level', 'revision_no', 'ref_id'];

    public function program() {
        return $this->belongsTo('App\Program');
    }

    public function curriculumCourses() {
        return $this->hasMany('App\CurriculumCourse');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function getPastVersion() {
        return Curriculum::where('program_id', $this->program_id)
            ->where('id','<>', $this->id)
            ->latest()
            ->get();
    }

    public function checkIfLatestVersion() {
        return !Curriculum::where('program_id', $this->program_id)
            ->where('revision_no', '>', $this->revision_no)
            ->exists();
    }

    public function getLatestVersion() {
        return Curriculum::where('program_id', $this->program_id)
            ->latest()
            ->first();
    }
}
