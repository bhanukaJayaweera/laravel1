<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script src="{{ asset('js/new.js') }}"></script>
    <title>Document</title>
</head>
<body>
    <div class = "container">
        <div class = "row col-md-12">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
        <a class="navbar-brand" href="#"><h2 class="text-center text-primary">Order Page</h2></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ route('product.upload') }}">Upload Excel</a>
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
        <a class="w3-bar-item w3-button" href="{{route('order.create')}}">Create Order</a>      
        <a class="w3-bar-item w3-button" href="{{route('dashboard')}}">Home</a>
        
        </div>
    </div>
    

    <div class="container col-md-6" style="margin-left:30%">
    @if(session()->has('success'))
            <!-- <div class="alert alert-warning d-flex align-items-center" role="alert" style="margin-left:3%">
                <div>{{session('success')}}</div>
            </div> -->
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{session('success')}}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>         
        </div>
        @endif
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal"style="margin-left:28%" data-bs-target="#exampleModal" >
    Launch demo modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form method="POST" action="">
            @csrf  
            <div class="input-group mb-3">
                    <label class="input-group-text" id="inputGroup-sizing-default">Order ID</label>
                    <input type="text" name="name" value="" class="form-control" readonly>
                </div>   
                <div class="input-group mb-3">
                    <label class="input-group-text" id="inputGroup-sizing-default">Customer ID</label>
                    <input type="text" name="customer_id" value="" class="form-control" readonly>
                </div>
                        <div class="input-group mb-3">
                    <label class="input-group-text" id="inputGroup-sizing-default">Customer Name</label>
                    <input type="text" name="customer_id" value="" class="form-control" readonly>
                </div>
                            

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
    </div>
   
    <div class="container" style="margin-left:28%">
        <div class="row col-md-9">
        <form id="selectedProductsForm" method="POST" action="{{route('order.select')}}">
        @csrf  
        <button type="submit" class="btn btn-primary mt-3" id="getSelectedRows">Get Selected Data</button>
        <table id="orderTable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Cus ID</th>
                <th>Customer Name</th>
                <th>Product ID</th>
                <th>Date</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>View</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td><input type="checkbox" class="orderCheckbox" name="order_ids[]" value="{{ $order->id }}"></td>
                    <td>{{$order->id}}</td>
                    <td>{{$order->customer_id}}</td>
                    <td>{{$order->customer->name}}</td>
                    <td>{{$order->product_id}}</td>
                    <td>{{$order->date}}</td>     
                    <td>{{$order->payment_type}}</td>  
                    <td>{{$order->amount}}</td>    
            
        </form>   
                    <td>
                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" href="{{route('order.view', ['order' => $order])}}" style="margin-left:28%" data-bs-target="#exampleModal" >
                Launch demo modal
                </button> -->
                        <a class="btn btn-primary" href="{{route('order.view', ['order' => $order])}}">View</a>
                    </td>
                    <td>
                        <a class="btn btn-success" href="{{route('order.edit', ['order' => $order])}}">Edit</a>
                    </td>
                    <td>
                        <form action="{{route('order.destroy', ['order'=>$order])}}" method='POST' onsubmit="return confirmDelete()">
                        @csrf
                        @method('delete') 
                            <input class="btn btn-danger" type="submit" value="delete" />
                        </form>
                    </td> 
        </tr>
            @endforeach
        </tbody>
        </table>
       
    </div>
    </div>
    <!-- Bootstrap JS & Popper.js -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Script -->
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this Order?');
    }
    $(document).ready(function () {
        $('#orderTable').DataTable({
            "paging": true,      // Enable Pagination
            "searching": true,   // Enable Search Box
            "ordering": true,    // Enable Sorting
            "info": true         // Show Info
        });

        $('#selectAll').on('change', function () {
            $('.orderCheckbox').prop('checked', this.checked);
        });

       


    });
 
</script>


</body>

</html>