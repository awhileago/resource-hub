<?php

namespace App\Http\Requests\Info;

use Illuminate\Foundation\Http\FormRequest;

class ParentInformationRequest extends FormRequest
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
            'fathers_name' => 'nullable',
            'fathers_occupation' => 'nullable',
            'fathers_company' => 'nullable',
            'mothers_name' => 'nullable',
            'mothers_occupation' => 'nullable',
            'mothers_company' => 'nullable',
            'average_monthly_income' => 'nullable',
        ];
    }
}
