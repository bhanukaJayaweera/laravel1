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
    <h1 class="text-center text-primary">View Customer</h1>
    <div class="container">
    <div class="row col-md-6" style="margin-left:25%">
    <form method="POST" action="">
    @csrf
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">ID</label>
            <input type="text" name="id" value="{{$customer->id}}" class="form-control" readonly>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Name</label>
            <input type="text" name="name" value="{{$customer->name}}" class="form-control" readonly>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Address</label>
            <input type="text" name="address" value="{{$customer->address}}" class="form-control" readonly>
        </div>
        <div class="input-group mb-3">
           
            <label class="input-group-text" id="inputGroup-sizing-default">Phone</label>
            <span class="input-group-text">+94</span>
            <input type="text" class="form-control" name="phone" value="{{$customer->phone}}" aria-label="Dollar amount (with dot and two decimal places)" readonly>
    
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Email</label>
            <input type="text" class="form-control" name="email" value="{{$customer->email}}" aria-label="Dollar amount (with dot and two decimal places)" readonly>
          
            
        </div>
        <div class="input-group mb-3">       
            <a type="button" href="{{route('customer.index')}}" class="btn btn-danger">Back</a>
        </div>

    </form>
    </div>
    </div>
    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
</body>
</html>