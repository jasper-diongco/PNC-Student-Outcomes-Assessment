<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'course_code' => $this->course_code,
            'description' => $this->description,
            'lec_unit' => $this->lec_unit,
            'lab_unit' => $this->lab_unit,
            'is_public' => $this->is_public,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'color' => $this->color,
            'college_id' => $this->college_id,
            'college' => $this->college->name,
            'college_code' => $this->college->college_code
        ];
    }
}
