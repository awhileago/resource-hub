<?php

namespace App\Http\Resources\Info;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserInformationResource extends JsonResource
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
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'suffix_name' => $this->when(! $this->relationLoaded('suffixName'), $this->suffix_name),
            'suffix_name' => $this->whenLoaded('suffixName'),
            'birthdate' => $this->birthdate,
            'gender' => $this->gender,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'mobile_verified_at' => $this->mobile_verified_at,
            'user_verified' => $this->user_verified,
            'is_active' => $this->is_active,
            'is_admin' => $this->is_admin,
            'address' => $this->address,
            'barangay_code' => $this->when(! $this->relationLoaded('barangay'), $this->barangay_code),
            'barangay' => $this->whenLoaded('barangay'),
            'lib_school_id' => $this->lib_school_id,
            'school' => $this->whenLoaded('school'),
            'lib_education_level_id' => $this->lib_education_level_id,
            'lib_year_level_id' => $this->lib_year_level_id,
            'scholar_flag' => $this->scholar_flag,
            'shiftee_flag' => $this->shiftee_flag,
            'irregular_flag' => $this->irregular_flag,
            'parents' => $this->whenLoaded('parents'),
            'academic_program' => $this->whenLoaded('academicProgram'),
            'year_level' => $this->whenLoaded('yearLevel'),
            'user_education' => $this->whenLoaded('userEducation'),
            'employment' => $this->whenLoaded('employment'),
            'reference' => $this->whenLoaded('reference'),
            'skill' => $this->whenLoaded('skill'),
            'photo_url' => $this->photo_url ? Storage::disk('public')->url($this->photo_url) : null,
            'cor_url' => $this->cor_url ? Storage::disk('public')->url($this->cor_url) : null,
            'grade_url' => $this->grade_url ? Storage::disk('public')->url($this->grade_url) : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
