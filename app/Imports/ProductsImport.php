<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'name'     => $row[0], // Column 1 in Excel
            'quantity' => $row[1], // Column 2 in Excel
            'price'    => $row[2], // Column 3 in Excel
        ]);
    }
}
