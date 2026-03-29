<?php

namespace App\Imports;

use App\Models\Business;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BusinessesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $isFirstRow = true;

        foreach ($rows as $row) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue; // skip header row
            }

            if (
                empty($row[1]) &&
                empty($row[4]) &&
                empty($row[8]) &&
                empty($row[9])
            ) {
                continue; // skip blank/invalid row
            }

            Business::create([
                'business_name' => isset($row[1]) ? trim((string) $row[1]) : null,
                'area' => isset($row[8]) ? trim((string) $row[8]) : null,
                'city' => isset($row[9]) ? trim((string) $row[9]) : null,
                'mobile_no' => isset($row[6]) ? trim((string) $row[6]) : null,
                'category' => isset($row[2]) ? trim((string) $row[2]) : null,
                'sub_category' => isset($row[5]) ? trim((string) $row[5]) : null,
                'address' => isset($row[4]) ? trim((string) $row[4]) : null,
            ]);
        }
    }
}