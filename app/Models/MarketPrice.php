<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $fillable = [
        'product_id',
        'market_id',
        'price',
        'price_date',
        'unit',
 
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }


    public function scopeLatestPrices($query)
    {
        return $query->where('price_date', static::max('price_date'));
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('price_date', $date);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rs. ' . number_format($this->price_date, 2) . ' per ' . $this->unit;
    }
}