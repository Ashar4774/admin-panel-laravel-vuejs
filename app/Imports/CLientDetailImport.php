<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CLientDetailImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 6; // Start from the 4th row (skip the first 3 rows)
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if(!isset($row[0], $row[1]) || empty(trim($row[0])) || empty(trim($row[1]))){
                continue;
            }
            $ref_no = $row[0];
            $name = $row[1];

            Client::create(['ref_no' => $ref_no, 'name' => $name]);
        }
    }
}
