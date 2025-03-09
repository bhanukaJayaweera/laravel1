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
    <h1 class="text-primary">Create a Product</h1>
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
    <form method="post" action="{{route('customer.store')}}">
    @csrf
    @method('post')    
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Name</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Address</label>
            <input type="text" name="address" class="form-control">
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Phone</label>
            <span class="input-group-text">+94</span>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Email</label>
            <input type="text" name="email" class="form-control">
        </div>
        <div class="input-group mb-3">
            <input type="submit" value="Save product" class="btn btn-success"/>
            <a type="button" href="{{route('product.index')}}" class="btn btn-danger">Back</a>
        </div>
    </form>
</div>
</div>
    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>