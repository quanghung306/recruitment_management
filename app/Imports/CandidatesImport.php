<?php

namespace App\Imports;

use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\ToModel;

class CandidatesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Candidate([
            'name' => $row[0],
            'email' => $row[1],
            'phone' => $row[2],
            'status' => $row[3],
            'cv_path' => $row[4],
        ]);
    }
}
