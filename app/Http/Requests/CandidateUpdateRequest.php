<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'phone' => 'sometimes|required|string|max:20',
            'skills' => 'sometimes|required|array',
            'status' => 'required|in:new,interviewed,hired,rejected',
            'user_id' => 'nullable|required|exists:users,id'
        ];
    }
}
