<?php

namespace App\Services;

use App\Models\Candidate;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CandidateService
{
    // Lấy tất cả ứng viên với filter
    public function getAllCandidates($filters = [])
    {
        $query = Candidate::with(['user', 'skills'])
            ->orderBy('created_at', 'desc');

        // Filter theo skills
        if (!empty($filters['skills'])) {
            $query->whereHas('skills', function ($q) use ($filters) {
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
            if (request()->hasFile('cv')) {
                $path = request()->file('cv')->store('cv_files', 'public');
                $data['cv_path'] = $path;
            }


            return $candidate->load('skills');
        });
    }

    // Cập nhật ứng viên
    public function updateCandidate(Candidate $candidate, $data)
    {
        try {
            return DB::transaction(function () use ($candidate, $data) {
                $skills = Arr::pull($data, 'skills', null);

                // Xử lý file trước khi update
                if (request()->hasFile('cv_path')) {
                    if ($candidate->cv_path && Storage::disk('public')->exists($candidate->cv_path)) {
                        Storage::disk('public')->delete($candidate->cv_path);
                    }

                    $path = request()->file('cv_path')->store('cv_files', 'public');
                    $data['cv_path'] = $path;
                }

                $candidate->update($data);

                if ($skills !== null) {
                    $candidate->skills()->sync($skills);
                }

                Log::info('Updated candidate: ' . $candidate->id, [
                    'data' => $data,
                    'cv_path' => $data['cv_path'] ?? null,
                ]);
                return $candidate->load('skills');
            });
        } catch (Exception $e) {
            Log::error('Error updating candidate: ' . $e->getMessage());
            throw $e;
        }
    }


    // Xóa ứng viên
    public function deleteCandidate(Candidate $candidate)
    {
        return DB::transaction(function () use ($candidate) {
            if ($candidate->cv_path && Storage::disk('public')->exists($candidate->cv_path)) {
                Storage::disk('public')->delete($candidate->cv_path);
            }
            $candidate->skills()->detach();
            return $candidate->delete();
        });
    }
}
