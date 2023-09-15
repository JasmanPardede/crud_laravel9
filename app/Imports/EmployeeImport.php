<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Employee([
            'nama' => $row[1],
            'jenis_kelamin' => $row[2],
            'hp' => $row[3],
            'foto' => $row[4]
        ]);
    }
}
