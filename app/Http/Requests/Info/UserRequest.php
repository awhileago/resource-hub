<?php

namespace App\Http\Requests\Info;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'suffix_name' => 'required|exists:lib_suffix_names,code',
            'gender' => 'required',
            'birthdate' => 'required|date',
            'contact_number' => 'required|min:11|max:13', //'required|min:11|max:11|unique:users' . (request()->has('id') ? ',contact_number, ' . request()->input('id') : ''),
            //'username' => 'required|min:4|unique:users',// . (request()->has('id') ? ',username, ' . request()->input('id') : ''),
            'email' => 'nullable|email|unique:users'.(request()->has('id') ? ',email, '.request()->input('id') : ''),
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                //->uncompromised()
            ],
            'password_confirmation' => 'required:password',
            'address' => 'required',
            'barangay_code' => 'nullable|exists:barangays,psgc_10_digit_code',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max size 2MB
            'cor_url' => 'nullable|mimes:pdf|max:5120',   // Max size 5MB
            'grade_url' => 'nullable|mimes:pdf|max:5120',   // Max size 5MB
            'is_active' => 'nullable',
            'is_admin' => 'nullable',
            'irregular_flag' => 'nullable|boolean',
            'scholar_flag' => 'nullable|boolean',
            'shiftee_flag' => 'nullable|boolean',

            'ofw_flag' => 'nullable|boolean',
            'fathers_name' => 'nullable',
            'fathers_occupation' => 'nullable',
            'fathers_company' => 'nullable',
            'mothers_name' => 'nullable',
            'mothers_occupation' => 'nullable',
            'mothers_company' => 'nullable',
            'average_monthly_income' => 'nullable',

            'lib_school_id' => 'required|exists:lib_schools,id',
            'scholar_flag' => 'nullable|boolean',
            'shiftee_flag' => 'nullable|boolean',
            'irregular_flag' => 'nullable|boolean',

            'lib_education_level_id' => 'required|exists:lib_education_levels,id',
            'lib_academic_program_id' => 'required|exists:lib_academic_programs,id',
            'lib_year_level_id' => 'required|exists:lib_year_levels,id'
        ];
    }
}
