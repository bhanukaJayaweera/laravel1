<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Market;
use App\Models\MarketPrice;

use Illuminate\Http\Request;

class MarketPriceController extends Controller
{
    // Show all fruits with their latest prices
    public function index()
    {
        $products = Product::with(['price'])->get();
        
        return view('Market.index', compact('products'));
    }

    // Show prices by market
    public function byMarket($marketId)
    {
        $market = Market::with(['price' => function($query) {
            $query->latest('price_date')->groupBy('product_id');
        }])->findOrFail($marketId);

        return view('Market.market', compact('market'));
    }

    // Show price history for a specific fruit
    public function history($productId)
    {
        $product = Product::with(['price' => function($query) {
            $query->orderBy('price_date', 'desc');
        }])->findOrFail($productId);

        return view('Market.history', compact('product'));
    }
}
