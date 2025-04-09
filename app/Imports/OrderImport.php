<?php

namespace App\Imports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class OrderImport implements ToCollection, WithHeadingRow
{
   
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            // foreach ($rows as $row) {

            //     // Create Order
            //     $order = Order::updateOrCreate(
            //         ['customer_id'  =>  $row['customer_id']],
            //         [                
            //         'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('Y-m-d'),
            //         'payment_type' => $row['payment_type'], // Column 4in Excel
            //         'amount' => $row['amount'], // Column 5 in Excel
            //         ]
            // );

            // To track already created orders during this import
            $ordersMap = [];

            foreach ($rows as $row) {
            // Create a unique key for the order (customer_id + date + payment_type + amount)
                $key = $row['customer_id'] . '|' .
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('Y-m-d');
                  

            if (!isset($ordersMap[$key])) {
                // Create a new Order
                $ordersMap[$key] = Order::create([
                    'customer_id'  => $row['customer_id'],
                    'date'         => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('Y-m-d'),
                    'payment_type' => $row['payment_type'],
                    'amount'       => $row['amount'],
                ]);
            }

            $order = $ordersMap[$key];

                // Attach or sync product with quantity
                $order->products()->syncWithoutDetaching([
                    $row['product_id'] => ['quantity' => $row['quantity']]
                ]);
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