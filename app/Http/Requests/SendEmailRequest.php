<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
                'interview_id' => 'required|exists:interviews,id',
                'candidate_email' => 'required|email',
                'email_type' => 'required|in:invitation,reminder,result',
                'subject' => 'required|string',
                'content' => 'required|string',
        ];
    }
}
