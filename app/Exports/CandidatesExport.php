<?php

namespace App\Exports;

use App\Models\Candidate;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CandidatesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        
        return Candidate::select('id', 'name', 'email', 'phone', 'status', 'cv_path')
            ->get()
            ->map(function ($candidate) {
                return [
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'email' => $candidate->email,
                    'phone' => $candidate->phone,
                    'status' => $candidate->status,
                    'cv_path' => $candidate->cv_path
                        ? '=HYPERLINK("' . url(Storage::url($candidate->cv_path)) . '", "' . basename('download cv') . '")'
                        : '',
                ];
            });
    }
    public function headings(): array
    {
        return ['Id', 'Name', 'Email', 'Phone', 'Status', 'Cv_path'];
    }
}
