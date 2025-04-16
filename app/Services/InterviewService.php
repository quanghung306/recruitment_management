<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Interview;
use Illuminate\Http\Request;

class InterviewService
{
    public function getAllInterview($filters)
    {
        $query = Interview::with(['candidate', 'interviewer']);

        return $query->paginate(5);
    }
    public function createInterview(array $data): Interview
    {
        // Kiểm tra dữ liệu hợp lệ
        if (empty($data['candidate_id']) || empty($data['round']) || empty($data['interview_date'])) {
            throw new \Exception("Dữ liệu không đầy đủ.");
        }

        return Interview::create([
            'candidate_id' => $data['candidate_id'],
            'interviewer_id' => $data['interviewer_id'] ?? null,
            'interview_date' => $data['interview_date'],
            'round' => $data['round'],
            'interview_result' => $data['result'] ?? null
        ]);
    }

    public function update(Interview $interview, array $data): Interview
    {

        $interview->update([
            'candidate_id' => $data['candidate_id'],
            'interviewer_id' => $data['interviewer_id'] ?? null,
            'interview_date' => $data['interview_date'],
            'round' => $data['round'],
            'interview_result' => $data['result'] ?? null
        ]);

        return $interview;
    }


    public function delete(Interview $interview): void
    {
        // Xóa vòng phỏng vấn
        $interview->delete();
    }
}
