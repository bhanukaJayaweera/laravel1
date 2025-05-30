<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>

    <!-- <x-slot name="header">
    </x-slot> -->
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
       <!-- Font Awesome CDN (Add to <head> section) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="{{ asset('js/new.js') }}"></script>
    <title>Document</title>
    <style>

    </style>
</head>
<body>
<x-slot name="header">
    <h2 class="text-4xl font-bold text-orange-600 text-center leading-snug" style="color: #f97316;">
        {{ __('Current Fruit Prices in Sri Lanka') }}
    </h2>
</x-slot> 
<div class="container">
    <h1>Current Fruit Prices in Sri Lanka</h1>
    
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card">
                <!-- @if($product->image)
                <img src="{{ asset('storage/'.$fruit->image) }}" class="card-img-top" alt="{{ $fruit->name }}">
                @endif -->
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                   @if($product->marketPrice->isNotEmpty())
                        @foreach($product->marketPrice->take(1) as $price)
                        <p class="card-text">
                             Current Price: Rs. {{ number_format($price->price, 2) }} per {{ $price->unit }}
                            <br>
                            <small class="text-muted">
                                    @ {{ $price->market->name }}, 
                                    {{ $price->market->district }}
                                   ({{ \Carbon\Carbon::parse($price->price_date)->format('Y-m-d') }})
                            </small>
                        </p>
                        @endforeach
                    @else
                        <p class="card-text text-muted">Price not available</p>
                    @endif
                    <a href="{{ route('fruit.history', $product->id) }}" class="btn btn-sm btn-outline-primary">
                        View History
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

</body>
</x-app-layout>
</html>
