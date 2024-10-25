<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserEducationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'user_id' => 'required|exists:users,id',
            'lib_education_level_id' => 'required|exists:lib_education_levels,id',
            'lib_academic_program_id' => 'nullable|exists:lib_academic_programs,id',
            'school_name' => 'required',
            'start_year' => 'required',
            'end_year' => 'required'
        ];
    }
}
