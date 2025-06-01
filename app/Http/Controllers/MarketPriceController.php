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
        //$products = Product::with(['price'])->get();
        // Use:
    $products = Product::with(['marketPrice.market'])->get();
    return view('Market.index', compact('products'));

        // public function index(Request $request)
        // {
        //     $prices = Price::with(['fruit', 'market'])
        //         ->latest('date')
        //         ->when($request->district, function($query, $district) {
        //             return $query->whereHas('market', function($q) use ($district) {
        //                 $q->where('district', $district);
        //             });
        //         })
        //         ->when($request->fruit, function($query, $fruit) {
        //             return $query->whereHas('fruit', function($q) use ($fruit) {
        //                 $q->where('name', 'like', "%$fruit%");
        //             });
        //         })
        //         ->paginate(20);

        //     return FruitPriceResource::collection($prices);
        // }
    }

    // Show prices by market
    public function byMarket($marketId)
    {
        $market = Market::with(['marketPrice' => function($query) {
            $query->with(['product'])
                    ->orderBy('price_date')
                    ->whereIn('id', function($subQuery) {
                        $subQuery->selectRaw('MAX(id)')
                                ->from('market_prices')
                                ->groupBy('product_id');
                    });
        }])->findOrFail($marketId);

        return view('Market.market', compact('market'));
    }

    // Show price history for a specific fruit
    public function history($productId)
    {
        $product = Product::with(['marketPrice' => function($query) {
            $query->orderBy('price_date')
            ->with('market');
        }])->findOrFail($productId);

        return view('Market.history', compact('product'));
    }
}
