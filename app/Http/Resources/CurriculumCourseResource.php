<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CourseRequisiteResource;

class CurriculumCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'curriculum_id' => $this->curriculum_id,
            'course_id' => $this->course_id,
            'year_level' => $this->year_level,
            'semester' => $this->semester,
            'course_code' => $this->course->course_code,
            'description' => $this->course->description,
            'lec_unit' => $this->course->lec_unit,
            'lab_unit' => $this->course->lab_unit,
            'pre_requisites' => CourseRequisiteResource::collection($this->courseRequisites)
        ];
    }
}
