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
use Illuminate\Support\Facades\Log;

class MarketImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        
        Log::info('Starting import process');
        DB::beginTransaction();

        try {
            $pricesMap = [];

        foreach ($rows as $row) {
            Log::info('Processing row: ', $row->toArray());
            $dateString = str_replace('--', '-', $row['price_date']);
            // Handle date conversion
            $priceDate = is_numeric($dateString) 
                ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['price_date'])
                : \Carbon\Carbon::createFromFormat('Y-m-d', $dateString);

            $key = $row['product_id'] . '|' . $row['market_id'] . '|' . $priceDate->format('Y-m-d');

            if (!isset($pricesMap[$key])) {
                Log::info('Creating/updating record for key: ' . $key);
                $pricesMap[$key] = MarketPrice::updateOrCreate(
                [
                    'market_id' => $row['market_id'],
                    'product_id' => $row['product_id'],
                    'price_date' => $priceDate->format('Y-m-d'),
                ],
                [
                    'price' => $row['price'],
                    'unit' => $row['unit'],
                ]
            );
            }
        }

            DB::commit();
            Log::info('Import completed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
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