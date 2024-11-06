<?php

namespace App\Http\Requests\Posting;

use Illuminate\Foundation\Http\FormRequest;

class PostingMessageTemplateRequest extends FormRequest
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
            // 'is_approved' => 'required|boolean',
            'mobile_message_approved' => 'required',
            'mobile_message_rejected' => 'required',
            'email_message_approved' => 'required',
            'email_message_rejected' => 'required'
        ];
    }
}
