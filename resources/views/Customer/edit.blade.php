
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
    <h1 class="text-center text-primary">Edit a Customer</h1>
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
    <form method="post" action="{{route('customer.update',['customer'=>$customer])}}">
    @csrf
    @method('put')    
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Name</label>
            <input type="text" name="name" value="{{$customer->name}}" class="form-control">
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Address</label>
            <input type="text" name="address" value="{{$customer->address}}" class="form-control">
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Phone</label>
            <span class="input-group-text">+94</span>
            <input type="text" class="form-control" name="phone" value="{{$customer->phone}}" >
            
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text" id="inputGroup-sizing-default">Email</label>
            <input type="text" name="email" value="{{$customer->email}}" class="form-control">
        </div>
        <div class="input-group mb-3">
            <input type="submit" value="Edit Product" class="btn btn-success"/>
            <a type="button" href="{{route('customer.index')}}" class="btn btn-danger">Back</a>
        </div>
    </div>
    </div>
    </form>
    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>