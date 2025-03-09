<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
    <h1 class="text-primary">Place an Order</h1>
    <div class="alert alert-danger" role="alert">
        @if($errors -> any())
        <ul>
            @foreach($errors -> all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
        @endif
    </div>
    <div class="container row row-cols-4">
    <form method="post" action="{{route('order.store')}}">
    @csrf
    @method('post')    
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Customer</label>
            <select class="form-select" name="customer_id" required>
                <option value="">-- Choose a Customer --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Customer</label>
            <select class="form-select" name="product_id" required>
                <option value="">-- Choose a Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Date</label>
            <input type="date" name="date" class="form-control">
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Payment Type</label>
            <select class="form-select" name="payment_type" required>
                <option value="">-- Choose a Type --</option>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
            </select>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Amount</label>
            <input type="text" name="amount" class="form-control">
        </div>
        <div class="input-group mb-3">
            <input type="submit" value="Save product" class="btn btn-success"/>
            <a type="button" href="{{route('order.index')}}" class="btn btn-danger">Back</a>
        </div>
    </form>
</div>
</div>
    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>