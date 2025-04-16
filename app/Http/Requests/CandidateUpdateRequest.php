<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class CandidateUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:candidates,email,' . $this->route('candidate')->id,
            'phone' => 'sometimes|required|digits_between:1,20',
            'skills' => 'sometimes|required|array',
            'status' => 'required|in:new,interviewed,hired,rejected',
            'cv_path' => 'sometimes|nullable|file|mimes:pdf,doc,docx',
            'user_id' => 'nullable|required|exists:users,id'
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
