<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEducationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'lib_education_level_id' => $this->lib_education_level_id,
            'educationLevel' => $this->whenLoaded('educationLevel'),
            'lib_academic_program_id' => $this->lib_academic_program_id ,
            'academicProgram' => $this->whenLoaded('academicProgram'),
            'school_name' => $this->school_name,
            'start_year' => $this->start_year,
            'end_year' => $this->end_year,
        ];
    }
}
