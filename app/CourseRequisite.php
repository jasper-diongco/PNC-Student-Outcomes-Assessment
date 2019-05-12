<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CurriculumCourse;

class CourseRequisite extends Model
{
    protected $fillable = ['curriculum_course_id', 'type', 'pre_req_id'];

    public function curriculumCourse() {
      return $this->belongsTo('App\CurriculumCourse');
    }

    public function preReq() {
      return CurriculumCourse::find($this->pre_req_id);
    }
}
