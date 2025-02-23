<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <div class = "container">
        <div class = "row col-md-12">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
        <a class="navbar-brand" href="#"><h2 class="text-center text-primary">Products Page</h2></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#">Upload Excel</a>
            </li>
            
        </ul>
        <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
  </div>
</nav>
    </div>
    </div>

    <div class = "container">
            <!-- Sidebar -->
        <div class="w3-sidebar w3-light-grey w3-bar-block" style="width:20%">
        <h3 class="w3-bar-item">Menu</h3>
        <a class="w3-bar-item w3-button" href="{{route('product.create')}}">Create Product</a>      
        <a class="w3-bar-item w3-button" href="{{route('dashboard')}}">Home</a>
        
        </div>
    </div>
    
    <div class="container" style="margin-left:25%">
        @if(session()->has('success'))
            <div class="alert alert-warning d-flex align-items-center" role="alert" style="margin-left:3%">
            <!-- <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg> -->
                <div>{{session('success')}}</div>
            </div>
        @endif
    </div>
    <div class="container" style="margin-left:28%">
        <div class="row col-md-9">
        <table class="table table-striped table-hover">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            @foreach($products as $product)
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->price}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{route('product.view', ['product' => $product])}}">View</a>
                    </td>
                    <td>
                        <a class="btn btn-success" href="{{route('product.edit', ['product' => $product])}}">Edit</a>
                    </td>
                    <td>
                        <form action="{{route('product.destroy', ['product'=>$product])}}" method='POST'>
                        @csrf
                        @method('delete') 
                            <input class="btn btn-danger" type="submit" value="delete" />
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
</div>
    </div>
    <!-- Bootstrap JS & Popper.js -->
    <script>
    function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    }
    function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</body>
</html>