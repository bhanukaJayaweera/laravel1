@can('handle orders')
<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>

    <!-- <x-slot name="header">
    </x-slot> -->
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
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
    <style>

    </style>
</head>
<body>
<x-slot name="header">
    <h2 class="text-4xl font-bold text-orange-600 text-center leading-snug" style="color: #f97316;">
        {{ __('Promotions Page') }}
    </h2>
</x-slot> 
    <!-- <div class = "container" id="topbar">
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
            <a class="nav-link" href="{{ route('order.upload') }}">Upload Excel</a>
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
    </div> -->

    <div class = "container">
            <!-- Sidebar -->
        <div class="w3-sidebar w3-light-grey w3-bar-block" style="width:15%">
        <h3 class="w3-bar-item">Menu</h3>
        <!-- <button type="button" class="btn btn-primary createOrder" data-bs-toggle="modal" data-bs-target="#orderModal">New Order <i class="fa fa-plus"></i></button><br/> <br> -->
        <button type="button" class="btn btn-primary createOrderProduct" data-bs-toggle="modal" data-bs-target="#orderproductModal"><i class="fa fa-plus"></i> Promotion </button>     
        <br><br><a class="btn btn-success" href="{{route('dashboard')}}"><i class="fa fa-home"></i> Home</a>
        <br><br><a class="btn btn-success" href="{{route('order.upload')}}"><i class="fa fa-plus"></i> Upload Excel</a>

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

        <!-- Modal for Viewing Selected Rows -->
        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            
            <div class="modal-dialog">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">Selected Promotions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBody">
                   
                    </div>
                </div>
            
            </div>
        </div>
        <!-- Message Modal -->
        <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Message</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
               
                    <div class="modal-body">
                    <p id="u" style="display: none;"></p>
                    <p id="uerror" style="display: none;">Error Updating Promotion</p>
                    <p id="s" style="display: none;"></p>
                    <p id="serror" style="display: none;">Error Saving Promotion</p>
                    <p id="derror" style="display: none;">Error Deleting Promotion</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                       
                    </div>
                
            </div>
        </div>
    </div>

    <!-- Modal view/update-->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalTitle">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form id="orderForm">
            @csrf  
            <div class="input-group mb-3">
                <label class="input-group-text">Promotion ID</label>
                <input type="text" name="id" id="id" class="form-control" disabled>
            </div> 
        
            <div class="input-group mb-3" id="product_div">
                    <label class="input-group-text" id="inputGroup-sizing-default">Product</label>
                    <select class="form-select" name="product_id" id="prod_id">
                        <option value=""></option>               
                    </select>
            </div>


               
            <!-- Hidden input field to store product data -->
            <input type="hidden" name="products" id="editData">   
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Description</label>
                <input type="text" name="description" id="description" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Percentage</label>
                <input type="text" name="discount_percentage" id="discount_percentage" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Usage Limit</label>
                <input type="text" name="usage_limit" id="usage_limit" class="form-control">
            </div>
            <!-- <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Payment Type</label>
                <select class="form-select" name="payment_type" id="payment_type" required>
                    <option value="">-- Choose a Type --</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                </select>
            </div> -->

             <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Active</label>
                <div style="padding: 5px">
                    @php
                        $statuses = ['yes', 'no'];
                    @endphp

                    @foreach($statuses as $status)
                        <div class="form-check form-check-reverse">
                            
                            <input class="form-check-input" type="radio" 
                                id="is_active" 
                                name="is_active" 
                                value="{{ $status }}">
                           
                            <label class="form-check-label" for="{{ $status }}">
                                {{ ucfirst($status) }}
                            </label>
                        </div>
                    @endforeach
                </div>
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
   
    <!-- Add new-Order_Product Modal -->
    <div class="modal fade" id="orderproductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalTitle1">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form id="orderProductForm">
            @csrf  
            <div class="input-group mb-3" hidden>
                <label class="input-group-text">Promotion ID</label>
                <input type="text" name="id" id="id" class="form-control">
            </div> 
            

                <div class="input-group mb-3">
                    <label class="input-group-text" id="inputGroup-sizing-default">Product</label>
                    <select class="form-select" name="product_id" id="product_id">
                        <option value=""></option>               
                    </select>
                </div>


                    <!-- Hidden input field to store product data -->
                <!-- <input type="hidden" name="products" id="productsData">                  -->
                <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Description</label>
                <input type="text" name="description" id="description" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Percentage</label>
                <input type="text" name="discount_percentage" id="discount_percentage" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Usage Limit</label>
                <input type="text" name="usage_limit" id="usage_limit" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Active</label>
                <div style="padding: 5px">
                    @php
                        $statuses = ['yes', 'no'];
                    @endphp

                    @foreach($statuses as $status)
                        <div class="form-check form-check-reverse">          
                            <input class="form-check-input" type="radio" 
                                id="is_active" 
                                name="is_active" 
                                value="{{ $status }}">          
                            <label class="form-check-label" for="{{ $status }}">
                                {{ ucfirst($status) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Payment Type</label>
                <select class="form-select" name="payment_type" id="payment_types" required>
                    <option value="">-- Choose a Type --</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                </select>
            </div> -->
           
                                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary new">Save changes</button>
            </div>
        </form>
        </div>
    </div>
    </div>
    <div class="container" style="margin-left:25%">
        <div class="row col-md-9">
        <!-- <form id="selectedProductsForm" method="POST" action="{{route('order.select')}}">
        @csrf   -->
        <!-- <button type="submit" class="btn btn-primary mt-3" id="getSelectedRows">Get Selected Data</button> -->
        
        <!-- <form id="actionForm">
        @csrf -->
        <!-- <input type="hidden" name="_method" value="DELETE"> -->
        <!-- Buttons -->
        <div id="buttons" style="padding:10px">
        <button type="button" class="btn btn-info" id="viewSelected"><i class="fa fa-eye"></i> View Selected</button>
        <button type="button" class="btn btn-danger" id="deleteSelected" style="margin-left:0%"><i class="fa fa-trash"></i> Bulk Delete</button>
        </div>
        <table id="orderTable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Promotion ID</th>
                <!-- <th>Cus ID</th> -->
                <th>Product Name</th>
                <!-- <th>Product ID</th> -->
                <th>Description</th>
                <th>Percentage</th>
                <th>Start Date</th>
                <th>End Date</th>              
                <th>Usage Limit</th>
                <th>Active</th>
                <th>View</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($promotions as $promotion)
                <tr>
                    <td><input type="checkbox" class="orderCheckbox" name="promotion_ids[]" value="{{ $promotion->id }}"></td>
                    <td>{{$promotion->id}}</td>
                    <td>{{$promotion->product->name}}</td>
                    <td>{{$promotion->description}}</td>
                    <td>{{$promotion->discount_percentage}}</td>
                    <td>{{$promotion->start_date}}</td>     
                    <td>{{$promotion->end_date}}</td>    
                    <td>{{$promotion->usage_limit}}</td>    
                    <td>
                    @php
                        $statusClass = match($promotion->is_active) {
                            'yes' => 'badge bg-warning text-dark',
                            'no' => 'badge bg-primary',
                            default => 'badge bg-secondary',
                        };
                    @endphp

                    <span class="{{ $statusClass }}">
                        {{ ucfirst($promotion->is_active) }}
                    </span>

                    </td>  
               
        </form>   
       
                    <td>
                    <button type="button" class="btn btn-primary viewOrder" data-id="{{ $promotion->id }}" data-bs-toggle="modal" data-bs-target="#orderModal"><i class="fa fa-eye"></i></button> 
                      
                    </td> 
                    <td>
                    <button type="button" class="btn btn-success editOrder" data-id="{{ $promotion->id }}" data-bs-toggle="modal" data-bs-target="#orderModal"><i class="fa fa-edit"></i></button> 
                       
                    </td>
                    <td>  
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <button type="button" class="btn btn-danger deleteOrder" data-id="{{ $promotion->id }}"><i class="fa fa-trash"></i></button>
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
    function confirmDelete() {
        return confirm('Are you sure you want to delete this Order?');
    }
    $(document).ready(function () {

    // Save new order (AJAX Form Submission)
    $("#orderProductForm").submit(function (e){
            e.preventDefault(); // Prevent default form submission        
            var id = $("#id").val();
            var formData = $(this).serialize();
            $.ajax({
                        url: "/promotion/store",
                        type: "POST",
                        data: formData,
                        success: function (response) {
                            //alert(response.message);
                            //location.reload(); // Refresh page

                            // if (response.invoice_url) {
                            //     window.open(response.invoice_url, '_blank'); // 👈 Opens in new tab
                            // } 
                            $("#orderproductModal").modal("hide"); // Close modal
                            $("#u").text(response.message).show(); 
                            $("#messageModal").modal("show");
                            
                            setTimeout(function () {
                                location.reload();
                            }, 2000); // Reload after 3 seconds
                
                        },
                        error: function (xhr) {
                            //alert("Error saving order!");
                            $("#orderproductModal").modal("hide"); // Close modal
                            $("#uerror").show();
                            $("#messageModal").modal("show");
                        },
                    });
                
        });

        
        //fetchcreateOrderProduct
        $(".createOrderProduct").click(function () {
            $("#is_active").prop("disabled", false);;
            $("#orderproductModal").show();
           // $("#orderForm")[0].reset(); // Clear Form
             $("#modalTitle1").text("New Promotion");
            // $("#orderForm input").prop("disabled", false); // Enable fields
            // $("#orderForm select").prop("disabled", false); // Enable fields
            // $(".save").prop("hidden", false) // Show Save Button    
            $.ajax({
                    url: "/promotion/newfetch",
                    type: "GET",
                    success: function (response) {
                        let dropdown1 = $("#product_id"); // Select dropdown
                        dropdown1.empty(); // Clear existing options
                        dropdown1.append('<option value="">Select Product</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.products, function(index, product) {
                            dropdown1.append('<option value="' + product.id + '" data-price="'+product.price+'">' + product.name + '</option>');
                        });
                       
                    }
            });
        });

    //view order
        $(".viewOrder").click(function () {
                var promotionId = $(this).data("id");
                $.ajax({
                    url: "/promotion/" + promotionId + "/change",
                    type: "GET",
                    success: function (response) {
                        $("#orderForm")[0].reset(); // Clear Form
                        $("#id").val(response.promotion.id);
                        $("#id").prop("disabled", true);
                        $("#product_div").prop("hidden", false);

                        let dropdown = $("#prod_id"); // Select dropdown
                        dropdown.empty(); // Clear existing options
                        dropdown.append('<option value="">Select Product</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.products, function(index, product) {
                            dropdown.append('<option value="' + product.id + '">' + product.name + '</option>');
                        });
                        $("#prod_id").val(response.promotion.product_id);
                        $("#prod_id").prop("disabled", true);

                        $("#description").val(response.promotion.description);
                        $("#description").prop("disabled", true);
                        $("#discount_percentage").val(response.promotion.discount_percentage);
                        $("#discount_percentage").prop("disabled", true);
                        $("#usage_limit").val(response.promotion.usage_limit);
                        $("#usage_limit").prop("disabled", true);
                        
                        $("#start_date").val(response.promotion.start_date);
                        $("#start_date").prop("disabled", true);
                        $("#end_date").val(response.promotion.end_date);
                        $("#end_date").prop("disabled", true);
         
                        $('input[name="is_active"]').prop('checked', false);
                        // Select the radio that matches the status
                        $(`input[name="is_active"][value="${response.promotion.is_active}"]`).prop('checked', true);
                        $('input[name="is_active"]').prop("disabled", true);
                        $("#modalTitle").text("View Promotion");
                        $(".save").prop("hidden", true);
                        $("#orderModal").modal("show");
                        
                    },
                });
            });
       
          // edit load customer data
          $(".editOrder").click(function () {
                var promotionId = $(this).data("id");
                $.ajax({
                    url: "/promotion/" + promotionId + "/change",
                    type: "GET",
                    success: function (response) {
                        $("#orderForm")[0].reset(); // Clear Form
                        $("#product_div").prop("hidden", false);
                        $("#id").val(response.promotion.id);  
                        //$("#prod_id").prop("hidden", false) 

                        let dropdown1 = $("#prod_id"); // Select dropdown
                        dropdown1.empty(); // Clear existing options
                        dropdown1.append('<option value="">Select Product</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.products, function(index, product) {
                            dropdown1.append('<option value="' + product.id + '" data-price="'+product.price+'">' + product.name + '</option>');
                        });
                        $("#prod_id").val(response.promotion.product_id);
                        $("#prod_id").prop("disabled", false);
                        $('input[name="is_active"]').prop("disabled", false);
                        $("#description").val(response.promotion.description);
                        $("#discount_percentage").val(response.promotion.discount_percentage);                    
                        $("#usage_limit").val(response.promotion.usage_limit);                    
                        $("#start_date").val(response.promotion.start_date);              
                        $("#end_date").val(response.promotion.end_date);
                       
                        // Select the radio that matches the status
                        $(`input[name="is_active"][value="${response.promotion.is_active}"]`).prop('checked', true);

                                          
                        $("#modalTitle").text("Edit Promotion");
                        $("#orderForm input").prop("disabled", false); // Enable fields
                        $("#orderForm select").prop("disabled", false); // Enable fields
                        $(".save").prop("hidden", false) // Show Save Button
                        $("#orderModal").modal("show");
                    },
                });
            });

    // Update Customer (AJAX Form Submission)
    $("#orderForm").submit(function (e){
            e.preventDefault(); // Prevent default form submission        
            var id = $("#id").val();
            let formData = new FormData(this);
          
            // Get CSRF token properly
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                        url: "/promotion/edit",
                        type: "POST",
                        data: formData,
                        processData: false,  // Required for FormData
                        contentType: false,  // Required for FormData
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (response) {
                            //alert(response.message);
                            //location.reload(); // Refresh page
                            $("#orderModal").modal("hide"); // Close modal
                            //$("#u").text(response.message)
                            //alert(response.message)
                            $("#u").text(response.message).show(); 
                            $("#messageModal").modal("show");
                           
                            setTimeout(function () {
                                location.reload();
                            }, 2000); // Reload after 3 seconds
                        },
                        error: function (xhr) {
                            //alert("Error saving order!");
                            $("#orderModal").modal("hide"); // Close modal
                            $("#uerror").show();
                            $("#messageModal").modal("show");
                        },
                    });
                });

        //new 
        $(".createOrder").click(function () {
            $("#orderForm")[0].reset(); // Clear Form
            $("#modalTitle").text("New Promotion");
            $("#orderForm input").prop("disabled", false); // Enable fields
            $("#orderForm select").prop("disabled", false); // Enable fields
            $(".save").prop("hidden", false) // Show Save Button    
            $.ajax({
                    url: "/promotion/newfetch",
                    type: "GET",
                    success: function (response) {

                        let dropdown1 = $("#product_id"); // Select dropdown
                        dropdown1.empty(); // Clear existing options
                        dropdown1.append('<option value="">Select Product</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.products, function(index, product) {
                            dropdown1.append('<option value="' + product.id + '">' + product.name + '</option>');
                        });
                                        
        
                    }
            });
        });

        // View Selected Orders
        document.getElementById('viewSelected').addEventListener('click', function() {
            let selectedOrders = [];
            document.querySelectorAll('input[name="promotion_ids[]"]:checked').forEach(checkbox => {
                let row = checkbox.closest('tr');
                let promotionData = {
                    id: row.cells[1].textContent,
                    product_name: row.cells[2].textContent,
                    description: row.cells[3].textContent,
                    discount_percentage: row.cells[4].textContent,
                    start_date: row.cells[5].textContent,
                    end_date: row.cells[6].textContent,
                    usage_limit: row.cells[7].textContent,
                    is_active: row.cells[8].textContent,
 
                };
                selectedPromotions.push(promotionData);
            });

            if (selectedPromotions.length > 0) {
                let modalBody = document.getElementById('modalBody');

                 // Create form dynamically inside the modal
                modalBody.innerHTML = `
                    <form id="selectedProductsForm" method="POST" action="{{ route('order.select') }}"  target="_blank">
                        @csrf
                        <ul>
                            ${selectedPromotions.map(promotion => 
                                `<li>
                                    <strong>Promotion ID:</strong> ${promotion.id} 
                                    | <strong>Production Name:</strong> ${promotion.product.name} 
                                    | <strong>Description:</strong> ${promotion.description} 
                                    | <strong>Discount Percentage:</strong> ${promotion.discount_percentage} 
                                    | <strong>Start Date:</strong> ${promotion.start_date} 
                                    | <strong>End Date:</strong> ${promotion.end_date} 
                                    | <strong>Usage Limit:</strong> ${promotion.usage_limit} 
                                    | <strong>Status:</strong> ${promotion.is_active} 
                                    <input type="hidden" name="promotion_ids[]" value="${promotion.id}">
                                </li>`
                            ).join('')}
                        </ul>
                        <button type="submit" class="btn btn-primary mt-3" id="getSelectedRows"><i class="fa fa-print"></i> Print Data</button>
                    </form>
                `;

                let viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                viewModal.show();
            } else {
                alert("No orders selected!");
            }
            
        });

  
        $('#orderTable').DataTable({
            "paging": true,      // Enable Pagination
            "searching": true,   // Enable Search Box
            "ordering": true,    // Enable Sorting
            "info": true         // Show Info
        });

        $('#selectAll').on('change', function () {
            $('.orderCheckbox').prop('checked', this.checked);
        });

    
        $(".deleteOrder").click(function () {
                    let promotionId = $(this).data('id'); // use `this`
                    $.ajax({
                                    url: "/promotion/" + promotionId,
                                    type: 'DELETE',                                   
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // include CSRF token
                                    },
                                    dataType: 'json', // Ensure we expect JSON response
                                    success: function (response) {
                                        $("#u").text(response.message).show(); 
                                        $("#messageModal").modal("show");
                                        
                                        setTimeout(function () {
                                            location.reload();
                                        }, 2000); // Reload after 3 seconds

                                           
                                    },
                                    error: function (xhr) {
                                        //alert("Error saving order!");
                                        $("#derror").show();
                                        $("#messageModal").modal("show");
                                    },
                    })

        });

              // Handle Delete Selected Orders
        // document.getElementById('actionForm').addEventListener('submit', function(e) {
            $("#deleteSelected").click(function () {
            let selectedPromotions = $('input[name="promotion_ids[]"]:checked');
            if (selectedPromotions.length > 0) {
                if (confirm("Are you sure you want to delete selected orders?")) {
                    let promotionIds = selectedPromotions.map(function () {
                        return $(this).val();
                    }).get();
                    
                    $.ajax({
                        url: "/promotion/delete-multiple",
                        type: 'DELETE',                         
                        data: {
                            promotion_ids: promotionIds,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json', // Ensure we expect JSON response
                        success: function (response) {
                            $("#u").text(response.message).show(); 
                            $("#messageModal").modal("show");
                            
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        },
                        error: function (xhr) {
                            $("#derror").show();
                            $("#messageModal").modal("show");
                        },
                    });
                }
            } else {
                alert("No orders selected for deletion!");
            }
        });


    });
 
</script>


</body>
</x-app-layout>
</html>
@endcan