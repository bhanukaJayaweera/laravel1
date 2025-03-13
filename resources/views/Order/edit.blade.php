
@include('Products.frame')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="text-center text-primary">Edit an Order</h1>
    <div class="container">
    <div class="row col-md-6" style="margin-left:25%">
        <!-- <div class="alert alert-danger" role="alert">
            @if($errors -> any())
            <ul>
                @foreach($errors -> all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
            @endif
        </div> -->
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>
            @if($errors -> any())
            <ul>
                @foreach($errors -> all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
            @endif
            </strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>         
        </div>
    </div>
    <div class="row col-md-6" style="margin-left:25%">
    <form method="post" action="{{route('order.update',['order'=>$order])}}">
    @csrf
    @method('put')    
    <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Customer</label>
            <select class="form-select" name="customer_id" required>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}" 
            {{ old('customer_id', $selectedCustomerId ?? '') == $customer->id ? 'selected' : '' }}>
                {{ $customer->name }}
            </option>
            @endforeach
            </select>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Product</label>
            <select class="form-select" name="product_id" required>
            @foreach($products as $product)
            <option value="{{ $product->id }}" 
            {{ old('product_id', $selectedProductId ?? '') == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
            @endforeach
            </select>
        </div>
    
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Date</label>
            <input type="date" name="date" value="{{$order->date}}" class="form-control" >
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Payment Type</label>
            <select class="form-select" name="payment_type" required>
                <!-- <option value="">-- Choose a Type --</option> -->
                <option value="cash" {{ old('payment_type', $selectedPaymentType ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="card" {{ old('payment_type', $selectedPaymentType ?? '') == 'card' ? 'selected' : '' }}>Card</option>
            </select>
        </div> 
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Amount</label>
            <input type="text" name="amount" value="{{$order->amount}}" class="form-control">
        </div>
        <div class="input-group mb-3">
            <input type="submit" value="Edit Product" class="btn btn-success"/>
            <a type="button" href="{{route('order.index')}}" class="btn btn-danger">Back</a>
        </div>
    </div>
    </div>
    </form>
    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>