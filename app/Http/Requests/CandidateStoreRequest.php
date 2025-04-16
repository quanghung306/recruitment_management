<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CandidateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email',
            'phone' => 'required|digits_between:1,20',
            'skills' => 'sometimes|array',
            'status' => 'sometimes|in:new,interviewed,hired,rejected',
            'cv_path' => 'nullable|file|mimes:pdf,doc,docx',
            'user_id' => 'nullable|exists:users,id'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withInput()
                ->with('errors', $validator->errors()->all())
        );
    }
}
