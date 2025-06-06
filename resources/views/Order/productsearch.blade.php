
<x-app-layout>
<x-slot name="header">
    <h2 class="text-4xl font-bold text-orange-600 text-center leading-snug" style="color:rgb(104, 196, 19);">
        {{ __('View Orders for the Products') }}
    </h2>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
       <!-- Font Awesome CDN (Add to <head> section) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="{{ asset('js/new.js') }}"></script>
    <title>Product Search</title>
    <style>

    </style>
</x-slot> 

@include('Order.frame')

    <div class="container col-md-6 brounded-lg border p-4" style="margin-left:25%; border: 1px solid #ccc; border-radius: 12px; padding: 16px;">
    <form method="GET" action="{{ route('order.search') }}" class="row row-cols-lg-auto g-3 align-items-center">
        <div class="col-12">
            <label for="inlineFormSelectPref">Product</label>
            <select class="form-select" id="inlineFormSelectPref" name="product_id" id="product_id">
            <option selected>-- Choose Product --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
            </select>
            
        </div>
          <div class="col-12">
            <label for="inlineFormSelectPref">Delivery Date</label>
            <input type="date" name="date" class="form-control" id="inlineFormSelectPref">
        </div>

        <div class="col-12">
            <div class="form-check">
            <input class="form-check-input" type="checkbox" id="inlineFormCheck">
            <label class="form-check-label" for="inlineFormCheck">
                Remember me
            </label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Search Orders</button>
        </div>

    </form>
    </div>
    @if(!empty($orders) && count($orders) > 0)
        <div class="container" style="margin-left:25%; border: 1px solid #ccc; border-radius: 12px; padding: 16px; width: 700px;">
        <table class="table table-striped table-bordered" id="productTable" style="width: 50%;">
            <thead>
            <tr>            
                <th>Order ID</th>
                <th>Customer Name</th> 
                <th>Product Name</th>     
                <th>Delivery Date</th>
                <th>Payment Type</th>
                <th>Quantity</th>
                <th>Status</th>

            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                @php
                    $quantity = null;
                    foreach ($order->products as $product) {
                        if ($product->id == $selectedProductId) {
                            $quantity = $product->pivot->quantity;
                            $product_name = $product->name;
                            break;
                        }
                    }
                @endphp
                <tr>                 
                    <td>{{$order->id}}</td>            
                    <td>{{$order->customer->name}}</td> 
                    <td>{{ $product_name ?? '—' }}</td>                
                    <td>{{$order->date}}</td>     
                    <td>{{$order->payment_type}}</td>  
                    <td>{{ $quantity ?? '—' }}</td> 
                    <td>@php
                        $statusClass = match($order->status) {
                            'new' => 'badge bg-warning text-dark',
                            'processing' => 'badge bg-primary',
                            'completed' => 'badge bg-success',
                            'cancelled' => 'badge bg-danger',
                            default => 'badge bg-secondary',
                        };
                    @endphp

                    <span class="{{ $statusClass }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    </td> 
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-center mt-4">No orders found for the selected product.</p>
    @endif
    <div class="col-12" style="margin-left:25%; padding:5px;">
            <a type="button" href="{{ route('order.index') }}" class="btn btn-danger">Back</a>
    </div>

     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS & Popper.js -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Script -->
<script>
     $(document).ready(function () {
        $('#productTable').DataTable({
            "paging": true,      // Enable Pagination
            "searching": true,   // Enable Search Box
            "ordering": true,    // Enable Sorting
            "info": true         // Show Info
        });
     })
</script>  
</x-app-layout>

