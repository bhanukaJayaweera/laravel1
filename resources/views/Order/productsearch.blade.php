
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
    <form class="row row-cols-lg-auto g-3 align-items-center">
        <div class="col-12">
            <label class="visually-hidden" for="inlineFormSelectPref">Products</label>
            <select class="form-select" id="inlineFormSelectPref" name="product_id" id="product_id">
            <option selected>-- Choose Product --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
            </select>
            
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
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>

    </form>
    </div>
    <div class="container" style="margin-left:25%; border: 1px solid #ccc; border-radius: 12px; padding: 16px;">
    <table class="table table-striped table-bordered">
            <thead>
            <tr>            
                <th>Order ID</th>
                <th>Customer Name</th>     
                <th>Date</th>
                <th>Payment Type</th>
                <th>Amount</th>

            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>                 
                    <td>{{$order->id}}</td>            
                    <td>{{$order->customer->name}}</td>                 
                    <td>{{$order->date}}</td>     
                    <td>{{$order->payment_type}}</td>  
                    <td>{{$order->amount}}</td>    
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</x-app-layout>

