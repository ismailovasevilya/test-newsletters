<?php

namespace App\Import;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (count($row) && count(array_filter($row))) {
            $data = [
                "fio" => $row['fio'],
                "email" => $row['email'] ?? null,
                "phone_number" => preg_replace("/[^0-9+]+/", "", $row['telefon']),
                "birth_date" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['den_rozdeniia'])->format('Y-m-d')
            ];
            return Client::updateOrCreate($data);
        }
    }
}
