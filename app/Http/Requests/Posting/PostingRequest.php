<?php

namespace App\Http\Requests\Posting;

use Illuminate\Foundation\Http\FormRequest;

class PostingRequest extends FormRequest
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
            'date_published' => 'nullable|date|date_format:Y-m-d|before:tomorrow',
            'date_end' => 'required|date|date_format:Y-m-d|after:date_published',
            'lib_posting_category_id' => 'required|exists:lib_posting_categories,id',
            'title' => 'required',
            'description' => 'required',
            'slot' => 'required|integer',
            'address' => 'nullable',
            'barangay_code' => 'nullable|exists:barangays,psgc_10_digit_code',
            'coordinates' => 'required',
            'no_scholar_flag' => 'required',
            'no_ofw_flag' => 'required',
            'no_shiftee_flag' => 'required',
            'no_irregular_flag' => 'required',
        ];
    }
}
