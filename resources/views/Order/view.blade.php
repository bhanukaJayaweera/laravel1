@include('Products.frame')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }

    </style>
</head>
<body>
    <h1 class="text-center text-primary">View Order</h1>
    <div class="container">
    <div class="row col-md-6" style="margin-left:25%">
    <form method="POST" action="">
    @csrf  
    <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Order ID</label>
            <input type="text" name="name" value="{{$order->id}}" class="form-control" readonly>
        </div>   
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Customer ID</label>
            <input type="text" name="customer_id" value="{{$order->customer_id}}" class="form-control" readonly>
        </div>
                <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Customer Name</label>
            <input type="text" name="customer_id" value="{{$order->customer->name}}" class="form-control" readonly>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Product ID</label>
            <input type="text" name="product_id" value="{{$order->product_id}}" class="form-control" readonly>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Product Name</label>
            <input type="text" name="product_id" value="{{$order->product->name}}" class="form-control" readonly>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Date</label>
            <input type="text" name="date" value="{{$order->date}}" class="form-control" readonly>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Payment Type</label>
            <input type="text" class="form-control" name="payment_type" value="{{$order->payment_type}}" readonly>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Amount</label>
            <span class="input-group-text">Rs</span>
            <input type="text" class="form-control" name="amount" value="{{$order->amount}}" aria-label="Dollar amount (with dot and two decimal places)" readonly>       
            <span class="input-group-text">.00</span>
        </div>
        <div class="input-group mb-3">       
            <a type="button" href="{{route('order.index')}}" class="btn btn-danger">Back</a>
        </div>

    </form>
    </div>
    </div>
    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
</body>
</html>