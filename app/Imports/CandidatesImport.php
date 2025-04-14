<?php

namespace App\Imports;

use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CandidatesImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Candidate([
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'status' => $row['status'],
            'cv_path' => $row['cv_path'],
        ]);
    }
}
