<?php

namespace App\Imports;

use App\Models\MarketPrice;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class MarketImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $pricesMap = [];

        foreach ($rows as $row) {
            // Handle date conversion
            $priceDate = is_numeric($row['price_date']) 
                ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['price_date'])
                : \Carbon\Carbon::createFromFormat('Y-m-d', $row['price_date']);

            $key = $row['product_id'] . '|' . $row['market_id'] . '|' . $priceDate->format('Y-m-d');

            if (!isset($pricesMap[$key])) {
                $pricesMap[$key] = MarketPrice::updateOrCreate(
                    [
                        'market_id' => $row['market_id'],
                        'product_id' => $row['product_id'],
                        'price' => $row['price'],
                        'price_date' => $priceDate->format('Y-m-d'),    
                        'unit' => $row['unit'],
                    ]
                );
            }
        }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
 /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     //format date as the excel date is taken as a number
    //     $formattedDate = Carbon::instance(ExcelDate::excelToDateTimeObject($row['date']))->format('Y-m-d');

    // return new Order([
    //     'customer_id' => $row['customer_id'], // Column 1 in Excel
    //     'date' => $formattedDate, // Column 3 in Excel
    //     'payment_type' => $row['payment_type'], // Column 4in Excel
    //     'amount' => $row['amount'], // Column 5 in Excel
    // ]);
        
    // }