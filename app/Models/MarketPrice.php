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

    protected $casts = [
        'price_date' => 'date', // or 'datetime' if you need time information
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

 

    // Scope to get latest prices
    public function scopeLatestPrices($query)
    {
        return $query->where('price_date', function($subquery) {
            $subquery->selectRaw('MAX(price_date)')
                    ->from('market_prices')
                    ->whereColumn('product_id', 'market_prices.product_id');
        });
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('price_date', $date);
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price_date->format('Y-m-d');
    }
}