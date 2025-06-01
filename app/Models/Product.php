<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'quantity',
        'price',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orders_products')->withPivot('quantity')->withTimestamps();
    }

    public function price()
    {
        return $this->hasMany(MarketPrice::class);
    }

    // public function marketPrice($marketId = null)
    // {
    //     $query = $this->hasMany(MarketPrice::class)->latest('price_date');
        
    //     return $query;
    // }

    public function marketPrice()
    {
        return $this->hasMany(MarketPrice::class);
    }

      public function market()
    {
          return $this->belongsToMany(Market::class, 'market_prices')
                ->withPivot('price', 'unit', 'price_date');
    }

    public function currentMarketPrice()
    {
        return $this->hasOne(MarketPrice::class)
                ->where('market_id', 1)
                ->latest('price_date');
    }
}
