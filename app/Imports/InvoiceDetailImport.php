<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class InvoiceDetailImport implements ToCollection, withStartRow
{
    /**
    * @param Collection $collection
    */
    public function startRow(): int
    {
        return 6; // Start from the 4th row (skip the first 3 rows)
    }

    public function collection(Collection $rows)
    {
//        dump($rows);
//        dump(count($rows));
        foreach ($rows as $row) {
            if(!isset($row[0], $row[1]) || empty(trim($row[0])) || empty(trim($row[1]))){
                continue;
            }
            $client = Client::where('ref_no',$row[0])->select('id')->first();
            if($client) {
                $invoiceDataStartIndex = 4; // Adjust based on your columns
                $columnsPerInvoice = 7;

                for ($i = $invoiceDataStartIndex; $i <= count($row); $i += $columnsPerInvoice) {
//                    dd($i);
                    // Ensure there are enough columns for a complete invoice
                    if (!isset($row[$i], $row[$i + 1])) {
                        continue;
                    }

                    // Extract invoice details
                    $due_date = Date::excelToDateTimeObject($row[$i])->format('Y-m-d');
                    $amount_due = $row[$i + 1];
                    $year = $row[$i + 2] ? (string) $row[$i + 2] : Date::excelToDateTimeObject($row[$i])->format('y');
                    $rcd_date = Date::excelToDateTimeObject($row[$i + 3])->format('Y-m-d') ?? null;
                    $rcd_amount = $row[$i + 4] ?? null;
                    $payment_type = $row[$i + 5] == 'b' || $row[$i + 5] == 'bank' || $row[$i + 5] == 'Bank' ? 'bank' : (($row[$i + 5] == 'c' || $row[$i + 5] == 'cash' || $row[$i + 5] == 'Cash') ? 'cash' : null);

                    // Create the invoice record
                    Invoice::create([
                        'clients_id' => $client->id,
                        'due_date' => $due_date,
                        'amount' => $amount_due,
                        'invoice_year' => $year,
                        'rcd_due_date' => $rcd_date,
                        'rcd_amount' => $rcd_amount,
                        'payment_type' => $payment_type,
                    ]);
                }
//                dump(count($row));
//                dd($row);
            }

        }
    }
}
