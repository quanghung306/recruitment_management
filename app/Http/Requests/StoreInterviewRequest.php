<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'candidate_id' => 'required|exists:candidates,id',
            'interviewer_id' => 'nullable|exists:users,id',
            'interview_date' => 'required|date',
            'round' => 'required|string|in:cv_screening,hr_interview,technical_interview,skill_test,final_interview',
            'result' => 'nullable|string|in:pass,fail,pending'
        ];
    }
}
