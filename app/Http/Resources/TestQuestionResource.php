<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestQuestionResource extends JsonResource
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
            'title' => $this->title,
            'choices_count' => $this->choices->count(),
            'difficulty_level_id' => $this->difficulty_level_id,
            'difficulty_level_desc' => $this->difficultyLevel->description,
            'user' => $this->user,
            'created_at' => $this->created_at,
            'choices' => $this->choices,
            'tq_code' => $this->tq_code,
            'type_id' => $this->type_id
        ];
    }
}
