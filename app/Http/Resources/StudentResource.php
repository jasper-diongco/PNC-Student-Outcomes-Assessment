<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'student_id' => $this->student_id,
            'full_name' => $this->user->getFullName(),
            'email' => $this->user->email,
            'college_code' => $this->program->college->college_code,
            'program_code' => $this->program->program_code
        ];
    }
}
