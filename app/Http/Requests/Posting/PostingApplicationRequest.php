<?php

namespace App\Http\Requests\Posting;

use Illuminate\Foundation\Http\FormRequest;

class PostingApplicationRequest extends FormRequest
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
            'posting_id' => 'required|exists:postings,id',
            'is_save' => 'nullable',
            'is_applied' => 'nullable',
            'date_applied' => 'nullable|date|date_format:Y-m-d|before:tomorrow',
            'is_approved' => 'nullable',
            'remarks' => 'nullable',
        ];
    }
}
