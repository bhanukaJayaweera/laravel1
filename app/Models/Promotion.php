<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable =[
        'product_id',
        'name',
        'description',
        'discount_amount',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active',
        'promo_code',
        'usage_limit',
        'used_count',
    ];
     #eloquent relationships
     public function product()
     {
         return $this->belongsTo(Product::class);
     }
}
