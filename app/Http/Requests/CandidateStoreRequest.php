<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'phone' => 'required|string|max:20',
            'skills' => 'required|array',
            'status' => 'required|in:new,interviewed,hired,rejected',
            'user_id' => 'nullable|exists:users,id'
        ];
    }
}
