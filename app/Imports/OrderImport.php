<?php

namespace App\Imports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class OrderImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //format date as the excel date is taken as a number
        $formattedDate = Carbon::instance(ExcelDate::excelToDateTimeObject($row['date']))->format('Y-m-d');

    return new Order([
        'customer_id' => $row['customer_id'], // Column 1 in Excel
        'product_id' => $row['product_id'], // Column 2 in Excel
        'date' => $formattedDate, // Column 3 in Excel
        'payment_type' => $row['payment_type'], // Column 4in Excel
        'amount' => $row['amount'], // Column 5 in Excel
    ]);
        
    }
}
