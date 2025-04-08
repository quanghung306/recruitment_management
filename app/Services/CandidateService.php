<?php

namespace App\Services;

use App\Models\Candidate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CandidateService
{
    // Lấy tất cả ứng viên với filter
    public function getAllCandidates($filters = [])
    {
        $query = Candidate::with(['user', 'skills'])
                         ->orderBy('created_at', 'desc');

        // Filter theo skills
        if (!empty($filters['skills'])) {
            $query->whereHas('skills', function($q) use ($filters) {
                $q->whereIn('id', (array)$filters['skills']);
            });
        }

        // Filter theo status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter theo user
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Phân trang
        return $query->paginate(config('app.paginate', 5));
    }

    // Tạo ứng viên mới
    public function createCandidate($data)
    {

        return DB::transaction(function () use ($data) {
            $skills = Arr::pull($data, 'skills', []);
            $candidate = Candidate::create($data);

            if (!empty($skills)) {
                $candidate->skills()->sync($skills);
            }

            return $candidate->load('skills');
        });
    }

    // Cập nhật ứng viên
    public function updateCandidate(Candidate $candidate, $data)
    {
        return DB::transaction(function () use ($candidate, $data) {
            $skills = Arr::pull($data, 'skills', null);

            $candidate->update($data);

            if ($skills !== null) {
                $candidate->skills()->sync($skills);
            }

            return $candidate->load('skills');
        });
    }

    // Xóa ứng viên
    public function deleteCandidate(Candidate $candidate)
    {
        return DB::transaction(function () use ($candidate) {
            $candidate->skills()->detach();
            return $candidate->delete();
        });
    }
}
