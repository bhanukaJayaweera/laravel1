<?php

namespace App\Services;

use App\Models\MarketPrice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MarketDataService
{
    protected $apiBaseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.market_data.url');
        $this->apiKey = config('services.market_data.key');
    }

    public function fetchAndStorePrices(array $markets, date $date)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->apiBaseUrl . '/prices', [
                'markets' => implode(',', $markets),
                'date' => $date,
            ]);

            if ($response->successful()) {
                $prices = $response->json();
                $this->storePrices($prices);
                return true;
            }
            
            Log::error('Market API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Market data fetch error: ' . $e->getMessage());
            return false;
        }
    }

    protected function storePrices(array $prices)
    {
        $now = now();
        
        $dataToInsert = array_map(function($price) use ($now) {
            return [
                'market_id' => $price['market_id'],
                'product_id' => $price['product_id'],
                'price' => $price['price'],
                'unit' => $price['unit'],
                'price_date' => $price['price_date'],
                //'source' => $price['source'] ?? 'market_api',
                //'timestamp' => $price['timestamp'] ?? $now,
               
            ];
        }, $prices);

        MarketPrice::insert($dataToInsert);
    }
}