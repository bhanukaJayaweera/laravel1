<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Market extends Model
{
    //use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'district',
    ];

    public function prices()
    {
        return $this->hasMany(MarketPrice::class);
    }

    public function marketPrices()
    {
        return $this->hasMany(MarketPrice::class)
            ->where('price_date', MarketPrice::latest('price_date')->value('date'));
    }

   public function product()
    {
        return $this->hasMany(Product::class);
    }


    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->location,
            $this->district,
        ]));
    }
}