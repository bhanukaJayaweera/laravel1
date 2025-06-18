<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Market;
use App\Models\MarketPrice;
use App\Services\MarketDataService;  
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MarketImport;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Http;

class MarketPriceController extends Controller
{
    // Show all fruits with their latest prices
    public function index()
    {
        //$products = Product::with(['price'])->get();
        // Use:
    //$products = Product::with(['latestMarketPrice'])->get();
    $products = Product::with(['marketPrice' => function($query) {
        $query->latest('price_date');
    }])->get();
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

    protected $marketDataService;

    public function __construct(MarketDataService $marketDataService)
    {
        $this->marketDataService = $marketDataService;
    }

    public function fetchPricesAPI(Request $request)
    {
        $response = Http::get("")


        // $validated = $request->validate([
        //     'markets' => 'required|array',
        //     'markets.*' => 'string|max:10',
        //     'date' => 'sometimes|date|max:3',
        // ]);
        
        // $date = $request->input('date', '2025-05-30');
        
        // $success = $this->marketDataService->fetchAndStorePrices(
        //     $validated['markets'],
        //     $date
        // );
        
        // return response()->json([
        //     'success' => $success,
        //     'message' => $success ? 'Prices fetched and stored' : 'Failed to fetch prices'
        // ], $success ? 200 : 500);

    //      try {
    //     $success = $marketService->fetchAndStorePrices([$market->code], now()->format('Y-m-d'));
        
    //     return response()->json([
    //         'success' => $success,
    //         'message' => $success ? 'Prices updated successfully' : 'Failed to update prices',
    //         'data' => $success ? $market->fresh()->marketPrices : []
    //     ]);
    // } catch (\Exception $e) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Error: ' . $e->getMessage()
    //     ], 500);
    // }
 
    }

    
    public function showUploadForm()
    {
        if (auth()->user()->can('handle products')) {
            return view('Market.upload');
        }
    }

    public function fetchPricesExcel(Request $request)
    {
        Log::info('fetchPricesExcel method called');
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // limit to 2MB
        ]);

        Log::info('File validation passed');
        Log::info('File details: ', [$request->file('file')]);
        try {
            Excel::import(new MarketImport, $request->file('file'));
            return back()->with('success', 'Prices imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage())
                        ->withInput();
        }
    }
    
}
