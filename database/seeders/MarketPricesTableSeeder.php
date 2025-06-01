<?php

namespace Database\Seeders;

use App\Models\MarketPrice;
use App\Models\Product;
use App\Models\Market;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MarketPricesTableSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $markets = Market::all();
        $units = ['kg', '100g', 'dozen', 'each'];
        $today = Carbon::today();

        // Create prices for last 7 days
        for ($days = 0; $days < 7; $days++) {
            $date = $today->subDays($days);

            foreach ($products as $product) {
                foreach ($markets as $market) {
                    // Random price variation between 80% and 120% of base price
                    $price = $product->price * (0.8 + (mt_rand(0, 40) / 100));
                    
                    MarketPrice::create([
                        'product_id' => $product->id,
                        'market_id' => $market->id,
                        'price' => round($price, 2),
                        'price_date' => $date,
                        'unit' => $units[array_rand($units)],
                    ]);
                }
            }
        }
    }
}