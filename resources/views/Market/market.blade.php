<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $market->name }} - Fruit Prices</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        .market-header {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .price-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            height: 100%;
        }
        .price-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .price-change {
            font-weight: bold;
        }
        .price-up {
            color: #28a745;
        }
        .price-down {
            color: #dc3545;
        }
        .market-map {
            height: 300px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <x-slot name="header">
        <h2 class="text-center text-uppercase font-weight-bold">
            {{ $market->name }} Market Prices
        </h2>
    </x-slot> 
    
    <div class="container py-4">
        <!-- Market Information Header -->
        <div class="market-header">
            <div class="row">
                <div class="col-md-8">
                    <h3><i class="fas fa-store me-2"></i>{{ $market->name }}</h3>
                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>{{ $market->location }}, {{ $market->district }}</p>
                    <p class="mb-1"><i class="fas fa-info-circle me-2"></i>Daily updated fruit prices</p>
                </div>
                <div class="col-md-4">
                    <div class="market-map bg-light d-flex align-items-center justify-content-center">
                        <p class="text-center text-muted">
                            <i class="fas fa-map-marked-alt fa-3x mb-2"></i><br>
                            Map View Coming Soon
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Price Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Products Tracked</h5>
                        <p class="card-text display-4">{{ $market->marketPrices->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Lowest Price</h5>
                        <p class="card-text display-4">
                            Rs. {{ number_format($market->marketPrices->min('price'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Highest Price</h5>
                        <p class="card-text display-4">
                            Rs. {{ number_format($market->marketPrices->max('price'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <h4 class="mb-3"><i class="fas fa-apple-alt me-2"></i>Current Prices</h4>
        <div class="row">
            @foreach($market->marketPrices as $price)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card price-card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $price->product->name }}</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="h4">Rs. {{ number_format($price->price, 2) }}</span>
                            <span class="badge bg-secondary">{{ $price->unit }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($price->price_date)->format('M d, Y') }}
                            </small>
                            <a href="{{ route('fruit.history', $price->product->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chart-line"></i> History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Price Comparison Table -->
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Detailed Price Comparison
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="marketPricesTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Product</th>
                                <th>Price (LKR)</th>
                                <th>Unit</th>
                                <th>Last Updated</th>
                                <th>Price Trend</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($market->marketPrices as $price)
                            <tr>
                                <td>{{ $price->product->name }}</td>
                                <td>{{ number_format($price->price, 2) }}</td>
                                <td>{{ $price->unit }}</td>
                                <td>{{ \Carbon\Carbon::parse($price->price_date)->diffForHumans() }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-up me-1"></i> Stable
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('fruit.history', $price->product->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#marketPricesTable').DataTable({
                order: [[3, 'desc']],
                pageLength: 10
            });
        });
    </script>
</body>
</x-app-layout>
</html>