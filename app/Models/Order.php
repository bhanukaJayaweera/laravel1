<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    use HasFactory;
    protected $fillable =[
        'customer_id',
        'date',
        'payment_type',
        'amount',
        'status',
    ];

    #eloquent relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'orders_products')->withPivot('quantity')->withTimestamps();
    }

}
