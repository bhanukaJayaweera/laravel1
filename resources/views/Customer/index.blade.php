<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
     <!-- Font Awesome CDN (Add to <head> section) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="{{ asset('js/new.js') }}"></script>
    <title>Document</title>
</head>
<body>
    <div class = "container">
        <div class = "row col-md-12">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
        <a class="navbar-brand" href="#"><h2 class="text-center text-primary">Customer Page</h2></a>
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
        <!-- <a class="w3-bar-item w3-button" href="{{route('customer.create')}}">Create Customer</a>    -->
        <button type="button" class="btn btn-primary createCustomer" data-bs-toggle="modal" data-bs-target="#customerModal">New Customer <i class="fa fa-plus"></i></button>   
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

      <!-- Modal -->
      <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Message</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
               
                    <div class="modal-body">
                    <p id="u" style="display: none;"></p>
                    <p id="uerror" style="display: none;">Error Updating Customer</p>
                    <p id="s" style="display: none;"></p>
                    <p id="serror" style="display: none;">Error Saving Customer</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                       
                    </div>
                
            </div>
        </div>
    </div>

        <!-- Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Customer Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="customerForm">
                    <div class="modal-body">
                        @csrf  
                        <div class="input-group mb-3" hidden>
                            <label class="input-group-text">Customer ID</label>
                            <input type="text" name="id" id="id" class="form-control">
                        </div>   
                        <div class="input-group mb-3">
                            <label class="input-group-text">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div> 
                        <div class="input-group mb-3">
                            <label class="input-group-text">Gender</label><br>
                            <div class="form-check form-check-inline" style="margin-left:3%">
                            <input class="form-check-input" type="radio" name="gender" id="Male" value="Male">
                            <label class="form-check-label" for="Male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="Female" value="Female">
                            <label class="form-check-label" for="Female">Female</label>
                            </div>

                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text">Address</label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div> 
                        <div class="input-group mb-3">
                            <label class="input-group-text">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>             
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
    <div class="container" style="margin-left:28%">
        <div class="row col-md-9">
        <form id="selectedProductsForm" method="POST" action="{{route('customer.select')}}">
        @csrf  
        <button type="submit" class="btn btn-primary mt-3" id="getSelectedRows">Get Selected Data</button>
        <table id="customerTable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td><input type="checkbox" class="customerCheckbox" name="customer_ids[]" value="{{ $customer->id }}"></td>
                    <td>{{$customer->id}}</td>
                    <td>{{$customer->name}}</td>
                    <td>{{$customer->gender}}</td>
                    <td>{{$customer->address}}</td>
                    <td>{{$customer->phone}}</td>     
                    <td>{{$customer->email}}</td>     
            
        </form>   
                    <td>
                    <button type="button" class="btn btn-success viewCustomer" data-id="{{ $customer->id }}" data-bs-toggle="modal" data-bs-target="#customerModal"> <i class="fa fa-eye"></i></button>              
                    <!-- <a class="btn btn-primary" href="{{route('customer.view', ['customer' => $customer])}}">View</a> -->
                    </td>
                    <!-- <a class="btn btn-success" href="{{route('customer.edit', ['customer' => $customer])}}">Edit</a> -->
                    <td>
                    
                    <button type="button" class="btn btn-primary editCustomer" data-id="{{ $customer->id }}" data-bs-toggle="modal" data-bs-target="#customerModal"><i class="fa fa-edit"></i></button>              
                    </td>
                    <td>
                        <form action="{{route('customer.destroy', ['customer'=>$customer])}}" method='POST'>
                        @csrf
                        @method('delete') 
                            <button class="btn btn-danger" type="submit" value="delete"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>           
        </tr>
            @endforeach
        </tbody>
        </table>
       
    </div>
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

        $(".viewCustomer").click(function () {
                var customerId = $(this).data("id");
                $.ajax({
                    url: "/customer/" + customerId + "/change",
                    type: "GET",
                    success: function (response) {
                        $("#id").val(response.id);
                        $("#id").prop("disabled", true);
                        $("#name").val(response.name);
                        $("#name").prop("disabled", true);
                        if (response.gender) {
                            $("input[name=gender][value=" + response.gender + "]").prop("checked", true);
                        }
                        $("#Male").prop("disabled", true);
                        $("#Female").prop("disabled", true);
                        $("#address").val(response.address);
                        $("#address").prop("disabled", true);
                        $("#email").val(response.email);
                        $("#email").prop("disabled", true);
                        $("#phone").val(response.phone);
                        $("#phone").prop("disabled", true);
                        $(".save").prop("hidden", true);
                        $("#customerModal").modal("show");
                        
                    },
                });
            });
        // Open modal and load customer data
        $(".editCustomer").click(function () {
                var customerId = $(this).data("id");
                $.ajax({
                    url: "/customer/" + customerId + "/change",
                    type: "GET",
                    success: function (response) {
                        $("#customerForm")[0].reset(); // Clear Form
                        $("#id").val(response.id);
                        $("#name").val(response.name);
                        if (response.gender) {
                            $("input[name=gender][value=" + response.gender + "]").prop("checked", true);
                        }
                        $("#address").val(response.address);
                        $("#email").val(response.email);
                        $("#phone").val(response.phone);
                        $("#modalTitle").text("Edit Customer");
                        $("#customerForm input").prop("disabled", false); // Enable fields
                        $(".save").prop("hidden", false) // Show Save Button
                        $("#customerModal").modal("show");
                    },
                });
            });

            //new customer
            $(".createCustomer").click(function () {
                $("#customerForm")[0].reset(); // Clear Form
                $("#customerForm input").prop("disabled", false); // Enable fields
                
            });



            // Save or Update Customer (AJAX Form Submission)
            $("#customerForm").submit(function (e){
                e.preventDefault();
                var formData = $(this).serialize();

                var id = $("#id").val();
                if (id) {
                    $.ajax({
                        url: "/customer/store",
                        type: "POST",
                        data: formData,
                        success: function (response) {
                            //alert(response.message);
                            $("#customerModal").modal("hide"); // Close modal
                            $("#u").text(response.message).show(); 
                            $("#messageModal").modal("show");
                            //location.reload(); // Refresh page
                            setTimeout(function () {
                                location.reload();
                            }, 2000); // Reload after 3 seconds
                        },
                        error: function (xhr) {
                            //alert("Error saving customer!");
                            $("#customerModal").modal("hide"); // Close modal
                            $("#uerror").show();
                            $("#messageModal").modal("show");
                        },
                    });
                }
                else{
                    $.ajax({
                        url: "/customer/new",
                        type: "POST",
                        data: formData,
                        success: function (response) {
                            //alert(response.message);
                            $("#customerModal").modal("hide"); // Close modal
                            $("#s").text(response.message).show(); 
                            $("#messageModal").modal("show");
                            //location.reload(); // Refresh page
                            
                            setTimeout(function () {
                                location.reload();
                            }, 2000); // Reload after 2 seconds
                        },
                        error: function (xhr) {
                            //alert("Error saving customer!");
                            $("#customerModal").modal("hide"); // Close modal
                            $("#serror").show();
                            $("#messageModal").modal("show");
                        },
                    });
                }
            });

        $('#customerTable').DataTable({
            "paging": true,      // Enable Pagination
            "searching": true,   // Enable Search Box
            "ordering": true,    // Enable Sorting
            "info": true         // Show Info
        });

        $('#selectAll').on('change', function () {
            $('.customerCheckbox').prop('checked', this.checked);
        });


    });
 
</script>


</body>

</html>