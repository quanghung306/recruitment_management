<?php

namespace App\Services;

use App\Models\Candidate;

class CandidateService
{
    public function getAllCandidates($filters)
    {
        $query = Candidate::with('user', 'skills');

        if (!empty($filters['skills'])) {
            $query->whereJsonContains('skills', $filters['skills']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate(5);
    }

    public function createCandidate($data)
    {
        return Candidate::create($data);
    }

    public function updateCandidate($candidate, $data)
    {
        $candidate->update($data);
        return $candidate;
    }

    public function deleteCandidate($candidate)
    {
        return $candidate->delete();
    }
}
