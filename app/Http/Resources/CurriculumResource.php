<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CurriculumCourseResource;

class CurriculumResource extends JsonResource
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
            'program_id' => $this->program_id,
            'name' => $this->name,
            'description' => $this->description,
            'year' => $this->year,
            'user_id' => $this->user_id,
            'year_level' => $this->year_level,
            'curriculum_courses' => CurriculumCourseResource::collection($this->curriculumCourses),
            'created_at' => $this->created_at,
            'author' => $this->user->getFullName(),
            'revision_no' => $this->revision_no
        ];
    }
}
