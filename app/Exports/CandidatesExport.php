<?php

namespace App\Exports;

use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CandidatesExport implements FromCollection, WithHeadings
{
    public function collection()
    {

        return Candidate::select('id', 'name', 'email', 'phone', 'status','cv_path')->get();
    }

    public function headings(): array
    {
        return ['Id', 'Name', 'Email', 'Phone', 'Status','Cv_path'];
    }
}
