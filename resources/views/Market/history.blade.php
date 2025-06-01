<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price History - {{ $product->name }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        .price-chart-container {
            height: 400px;
            margin-bottom: 30px;
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
    </style>
</head>
<body>
    <x-slot name="header">
        <h2 class="text-4xl font-bold text-orange-600 text-center leading-snug" style="color: #f97316;">
            {{ __('Price History for ') }} {{ $product->name }}
        </h2>
    </x-slot> 
    
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            {{ $product->name }} Price Trends
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="price-chart-container">
                            <canvas id="priceChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            Detailed Price History
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="priceHistoryTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Market</th>
                                        <th>District</th>
                                        <th>Price (LKR)</th>
                                        <th>Unit</th>
                                        <th>Change</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $previousPrice = null;
                                    @endphp
                                    @foreach($product->marketPrice as $price)
                                        @php
                                            $change = $previousPrice ? $price->price - $previousPrice : 0;
                                            $changeClass = '';
                                            $changeIcon = '';
                                            
                                            if ($change > 0) {
                                                $changeClass = 'price-up';
                                                $changeIcon = 'fas fa-arrow-up';
                                            } elseif ($change < 0) {
                                                $changeClass = 'price-down';
                                                $changeIcon = 'fas fa-arrow-down';
                                            }
                                            
                                            $previousPrice = $price->price;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($price->price_date)->format('Y-m-d') }}</td>
                                            <td>{{ $price->market->name }}</td>
                                            <td>{{ $price->market->district }}</td>
                                            <td>{{ number_format($price->price, 2) }}</td>
                                            <td>{{ $price->unit }}</td>
                                            <td class="price-change {{ $changeClass }}">
                                                @if($change != 0)
                                                    <i class="{{ $changeIcon }}"></i>
                                                    {{ number_format(abs($change), 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#priceHistoryTable').DataTable({
                order: [[0, 'desc']],
                pageLength: 10
            });
            
            // Prepare chart data
            const dates = @json($product->marketPrice->pluck('price_date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            }));
            
            const prices = @json($product->marketPrice->pluck('price'));
            const markets = @json($product->marketPrice->pluck('market.name'));
            
            // Create chart
            const ctx = document.getElementById('priceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: '{{ $product->name }} Price (LKR)',
                        data: prices,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: 'Price (LKR)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                afterLabel: function(context) {
                                    const index = context.dataIndex;
                                    return 'Market: ' + markets[index];
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</x-app-layout>
</html>