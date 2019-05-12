<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseRequisiteResource extends JsonResource
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
            'type' => 'pre-requisite',
            'curriculum_course_id' => $this->curriculum_course_id,
            'pre_req_id' => $this->pre_req_id,
            'pre_req_code' => $this->preReq()->course->course_code,
            'pre_req_desc' => $this->preReq()->course->description,
            'year_level' => $this->preReq()->year_level,
            'semester' => $this->preReq()->semester
        ];
    }
}
