<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>

    <!-- <x-slot name="header">
    </x-slot> -->

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
        {{ __('Order Page') }}
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
        <button type="button" class="btn btn-primary createOrderProduct" data-bs-toggle="modal" data-bs-target="#orderproductModal"><i class="fa fa-plus"></i> Order </button>     
        <br><br><a class="btn btn-success" href="{{route('dashboard')}}"><i class="fa fa-home"></i> Home</a>
        <br><br><a class="btn btn-success" href="{{route('order.upload')}}"><i class="fa fa-plus"></i> Upload Excel</a>
        <br><br><a class="btn btn-danger" href="{{route('order.productsearch')}}"><i class="fa fa-eye"></i> View Ordered Products</a>    
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
                        <h5 class="modal-title" id="viewModalLabel">Selected Orders</h5>
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
                    <p id="uerror" style="display: none;">Error Updating Order</p>
                    <p id="s" style="display: none;"></p>
                    <p id="serror" style="display: none;">Error Saving Order</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                       
                    </div>
                
            </div>
        </div>
    </div>

    <!-- Modal -->
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
            <div class="input-group mb-3" hidden>
                <label class="input-group-text">Order ID</label>
                <input type="text" name="id" id="id" class="form-control">
            </div> 
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Customer</label>
                <select class="form-select" name="customer_id" id="customer_id" required>
                    <option value=""></option>
                   
                </select>
            </div>
            <div class="input-group mb-3" id="product_div">
                    <label class="input-group-text" id="inputGroup-sizing-default">Product</label>
                    <select class="form-select" name="product_id" id="prod_id">
                        <option value=""></option>               
                    </select>
            </div>
                <div class="mb-3" id="quantity_div">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" class="form-control">
                </div>    
                <button type="button" id="addProductUpdate" class="btn btn-primary">Add to Table</button>

                <div class="input-group mb-3">
                <table id="prodview" class="table mt-3">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                     
                        </tbody>
                    </table>
                </div>
            <!-- Hidden input field to store product data -->
            <input type="hidden" name="products" id="editData">   
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Total Amount</label>
                <input type="text" name="amount" id="amount" class="form-control" readonly>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Date</label>
                <input type="date" name="date" id="date" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Payment Type</label>
                <select class="form-select" name="payment_type" id="payment_type" required>
                    <option value="">-- Choose a Type --</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                </select>
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
                <label class="input-group-text">Order ID</label>
                <input type="text" name="id" id="id" class="form-control">
            </div> 
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Customer</label>
                <select class="form-select" name="customer_id" id="cus_id" required>
                    <option value=""></option>
                   
                </select>
            </div>
            <!-- <form id="productForm"> -->
                <div class="input-group mb-3">
                    <label class="input-group-text" id="inputGroup-sizing-default">Product</label>
                    <select class="form-select" name="product_id" id="product_id">
                        <option value=""></option>               
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantitys" class="form-control" class="form-control">
                </div>    
                <button type="button" id="addProduct" class="btn btn-primary">Add to Table</button>
            <!-- </form> -->
                
                    <table id="orderproduct" class="table mt-3">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBodyNew">
                            <!-- Selected products will be added here -->
                        </tbody>
                    </table>
                    
                    <!-- Hidden input field to store product data -->
                    <input type="hidden" name="products" id="productsData">                 
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Total Amount</label>
                <input type="text" name="amount" id="amounts" class="form-control" readonly>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Date</label>
                <input type="date" name="date" id="date" class="form-control">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Payment Type</label>
                <select class="form-select" name="payment_type" id="payment_types" required>
                    <option value="">-- Choose a Type --</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                </select>
            </div>
           
                                
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
        
        <form id="actionForm" method="POST">
        @csrf
        @method('DELETE')
        <!-- Buttons -->
        <div id="buttons" style="padding:10px">
        <button type="button" class="btn btn-info" id="viewSelected"><i class="fa fa-eye"></i> View Selected</button>
        <button type="submit" class="btn btn-danger" id="deleteSelected" style="margin-left:0%"><i class="fa fa-trash"></i> Bulk Delete</button>
        </div>
        <table id="orderTable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Order ID</th>
                <!-- <th>Cus ID</th> -->
                <th>Customer Name</th>
                <!-- <th>Product ID</th> -->
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
                    <!-- <td>{{$order->customer_id}}</td> -->
                    <td>{{$order->customer->name}}</td>
                    <!-- <td>{{$order->product_id}}</td> -->
                    <td>{{$order->date}}</td>     
                    <td>{{$order->payment_type}}</td>  
                    <td>{{$order->amount}}</td>    
               
        </form>   
       
                    <td>
                    <button type="button" class="btn btn-primary viewOrder" data-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#orderModal"><i class="fa fa-eye"></i></button> 
                        <!-- <a class="btn btn-primary" href="{{route('order.view', ['order' => $order])}}">View</a>
                    </td> -->
                    <td>
                    <button type="button" class="btn btn-success editOrder" data-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#orderModal"><i class="fa fa-edit"></i></button> 
                        <!-- <a class="btn btn-success" href="{{route('order.edit', ['order' => $order])}}">Edit</a> -->
                    </td>
                    <td>
                        <form action="{{route('order.destroy', ['order'=>$order])}}" method='POST'>
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
    function confirmDelete() {
        return confirm('Are you sure you want to delete this Order?');
    }
    $(document).ready(function () {
        //order-product Modal- add new product
        $("#addProduct").click(function () {
        let productId = $("#product_id").val();
        let productName = $("#product_id").find("option:selected").text(); 
        let quantity = $("#quantitys").val();
        let price = $("#product_id").find("option:selected").data('price');
        console.log({ productId, productName, quantity }); // ✅ Check what you're getting
        $.ajax({
                        url: "/orderproduct/checkInvent",
                        type: "POST",
                        data: {
                            productId: productId,
                            quantity: quantity,
                            _token: $('input[name="_token"]').val() // important for POST!
                        },
                        success: function (response) {
                            //alert(response.message);
                            //location.reload(); // Refresh page
                        if (response.status === 'success') {  
                            if (productId && quantity > 0) {
                                let row = `
                                    <tr data-id="${productId}" data-quantity="${quantity}" data-price="${price}">
                                        <td>${productId}</td>
                                        <td>${productName}</td>
                                        <td>${quantity}</td>
                                        <td>${price}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm removeProduct" id="remove">Remove</button>
                                        </td>
                                    </tr>
                                `;
                                let exists = false;
                                $("#productTableBodyNew tr").each(function () {
                                    if ($(this).data("id") == productId) {
                                        exists = true;
                                        return false;
                                    }
                                });
                                if (exists) {
                                    alert("This product is already added.");
                                    return;
                                }
                                $("#productTableBodyNew").append(row);
                                $("#products_id").val('');
                                $("#quantitys").val(1);
                            } else {
                                alert("Please select a product and enter a valid quantity.");
                            }
                                  
                        }
                        },
                        error: function (xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                alert(xhr.responseJSON.message);
                            } else {
                                alert('An error occurred');
                            }
                        }
                    });
       
    });

    //load full amount 
    $('#amounts').on('click', function () {
        let amount = 0; // Moved outside the loop
        $("#productTableBodyNew tr").each(function () {
                let quantity = $(this).data("quantity");
                let price = $(this).data('price');
                productPrice = quantity*price;
                amount += productPrice;
            });
        $('#amounts').val(amount); // Set to some input
    });


        $("#addProductUpdate").click(function () {
        let productId = $("#prod_id").val();
        let productName = $("#prod_id").find("option:selected").text(); 
        let quantity = $("#quantity").val();
        let price = $("#prod_id").find("option:selected").data('price');   
        if (productId && quantity > 0) {
            let row = `
                <tr data-id="${productId}" data-quantity="${quantity}" data-price="${price}" >
                    <td>${productId}</td>
                    <td>${productName}</td>
                    <td>${quantity}</td>
                    <td>${price}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeProduct">Remove</button>
                    </td>
                </tr>
            `;
            let exists = false;
            $("#productTableBody tr").each(function () {
                if ($(this).data("id") == productId) {
                    exists = true;
                    return false;
                }
            });
            if (exists) {
                alert("This product is already added.");
                return;
            }
            $("#productTableBody").append(row);
            $("#prod_id").val('');
            $("#quantity").val(1);
        } else {
            alert("Please select a product and enter a valid quantity.");
        }
    });

    $(document).on("click", ".removeProduct", function () {
        $(this).closest("tr").remove();
    });

    // Save new order (AJAX Form Submission)
    $("#orderProductForm").submit(function (e){
            e.preventDefault(); // Prevent default form submission        
            var id = $("#id").val();
            let selectedProducts = [];

            $("#productTableBodyNew tr").each(function () {
                let productId = $(this).data("id");
                let quantity = $(this).data("quantity");
                selectedProducts.push({ product_id: productId, quantity: quantity });
            
            });

            if (selectedProducts.length === 0) {
                alert("Please add at least one product.");
                e.preventDefault();
                return;
            }

            $("#productsData").val(JSON.stringify(selectedProducts));
            var formData = $(this).serialize();
            $.ajax({
                        url: "/orderproduct/store",
                        type: "POST",
                        data: formData,
                        success: function (response) {
                            //alert(response.message);
                            //location.reload(); // Refresh page

                            if (response.invoice_url) {
                                window.open(response.invoice_url, '_blank'); // 👈 Opens in new tab
                            } 
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
            
            $("#orderproductModal").show();
           // $("#orderForm")[0].reset(); // Clear Form
             $("#modalTitle1").text("New Order Product");
            // $("#orderForm input").prop("disabled", false); // Enable fields
            // $("#orderForm select").prop("disabled", false); // Enable fields
            // $(".save").prop("hidden", false) // Show Save Button    
            $.ajax({
                    url: "/order/newfetch",
                    type: "GET",
                    success: function (response) {
           
                        let dropdown = $("#cus_id"); // Select dropdown
                        dropdown.empty(); // Clear existing options
                        dropdown.append('<option value="">Select Customer</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.customers, function(index, customer) {
                            dropdown.append('<option value="' + customer.id + '">' + customer.name + '</option>');
                        });
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
                var orderId = $(this).data("id");
                $.ajax({
                    url: "/order/" + orderId + "/change",
                    type: "GET",
                    success: function (response) {
                        $("#orderForm")[0].reset(); // Clear Form
                        $("#id").val(response.order.id);
                        $("#id").prop("disabled", true);
                        $("#product_div").prop("hidden", true);
                        $("#quantity_div").prop("hidden", true);
                        $("#addProductUpdate").prop("hidden", true);
                        let dropdown = $("#customer_id"); // Select dropdown
                        dropdown.empty(); // Clear existing options
                        dropdown.append('<option value="">Select Customer</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.customers, function(index, customer) {
                            dropdown.append('<option value="' + customer.id + '">' + customer.name + '</option>');
                        });
                        $("#customer_id").val(response.order.customer_id);
                        $("#customer_id").prop("disabled", true);

                        let tableBody = $("#productTableBody");
                        tableBody.empty(); // Clear existing data

                        $.each(response.order.products, function (index, product) {
                            //Log::info('Product:',$product);
                            tableBody.append(`
                                <tr>
                                    <td>${product.id}</td>
                                    <td>${product.name}</td>
                                    <td>${product.pivot.quantity}</td>
                                    <td>${product.price}</td>
                                </tr>
                            `);
                        });
                        
                        $("#date").val(response.order.date);
                        $("#date").prop("disabled", true);
                        $("#payment_type").val(response.order.payment_type);
                        $("#payment_type").prop("disabled", true);
                        $("#amount").val(response.order.amount);
                        $("#amount").prop("disabled", true);
                        $("#modalTitle").text("View Order");
                        $(".save").prop("hidden", true);
                        $("#orderModal").modal("show");
                        
                    },
                });
            });
       
          // edit load customer data
          $(".editOrder").click(function () {
                var orderId = $(this).data("id");
                $.ajax({
                    url: "/order/" + orderId + "/change",
                    type: "GET",
                    success: function (response) {
                        $("#orderForm")[0].reset(); // Clear Form
                        $("#product_div").prop("hidden", false);
                        $("#quantity_div").prop("hidden", false);
                        $("#addProductUpdate").prop("hidden", false);
                        let dropdown = $("#customer_id"); // Select dropdown
                        dropdown.empty(); // Clear existing options
                        dropdown.append('<option value="">Select Customer</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.customers, function(index, customer) {
                            dropdown.append('<option value="' + customer.id + '">' + customer.name + '</option>');
                        });
                        $("#customer_id").val(response.order.customer_id);
                        $("#prod_id").prop("hidden", false) 

                        let dropdown1 = $("#prod_id"); // Select dropdown
                        dropdown1.empty(); // Clear existing options
                        dropdown1.append('<option value="">Select Product</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.products, function(index, product) {
                            dropdown1.append('<option value="' + product.id + '" data-price="'+product.price+'">' + product.name + '</option>');
                        });

                        let tableBody = $("#productTableBody");
                        tableBody.empty(); // Clear existing data

                        $.each(response.order.products, function (index, product) {
                            //Log::info('Product:',$product);
                            tableBody.append(`
                                <tr data-id="${product.id}" data-quantity="${product.pivot.quantity}" data-price="${product.price}">                               
                                    <td>${product.id}</td>
                                    <td>${product.name}</td>
                                    <td>${product.pivot.quantity}</td>
                                    <td>${product.price}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm removeProduct">Remove</button>
                                    </td>
                                </tr>
                            `);
                        });
                        // $(document).on("click", ".removeProduct", function () {
                        //     $(this).closest("tr").remove();
                        // });

                        $("#id").val(response.order.id);                
                        $("#date").val(response.order.date);
                        let dropdown2 = $("#payment_type"); // Select dropdown
                        dropdown2.empty(); // Clear existing options
                        dropdown2.append('<option value="">Select Option</option>'); // Default option
                        dropdown2.append('<option value="card">Card</option>'); // Default option
                        dropdown2.append('<option value="cash">Cash</option>'); // Default option
                        $("#payment_type").val(response.order.payment_type);
                        $("#amount").val(response.order.amount);
                        $("#modalTitle").text("Edit Order");
                        $("#orderForm input").prop("disabled", false); // Enable fields
                        $("#orderForm select").prop("disabled", false); // Enable fields
                        $(".save").prop("hidden", false) // Show Save Button
                        $("#orderModal").modal("show");
                    },
                });
            });

     //load full amount 
     $('#amount').on('click', function () {
        let amount = 0; // Moved outside the loop
        $("#productTableBody tr").each(function () {
                let quantity = $(this).data("quantity");
                let price = $(this).data('price');
                productPrice = quantity*price;
                amount += productPrice;
            });
        $('#amount').val(amount); // Set to some input
    });

    // Update Customer (AJAX Form Submission)
    $("#orderForm").submit(function (e){
            e.preventDefault(); // Prevent default form submission        
            var id = $("#id").val();
            let selectedProducts = [];

            $("#productTableBody tr").each(function () {
                let productId = $(this).data("id");
                let quantity = $(this).data("quantity");
                selectedProducts.push({ product_id: productId, quantity: quantity });
            
            });

            if (selectedProducts.length === 0) {
                alert("Please add at least one product.");
                e.preventDefault();
                return;
            }

            $("#editData").val(JSON.stringify(selectedProducts));
            var formData = $(this).serialize();
            $.ajax({
                        url: "/orderproduct/edit",
                        type: "POST",
                        data: formData,
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
            $("#modalTitle").text("New Order");
            $("#orderForm input").prop("disabled", false); // Enable fields
            $("#orderForm select").prop("disabled", false); // Enable fields
            $(".save").prop("hidden", false) // Show Save Button    
            $.ajax({
                    url: "/order/newfetch",
                    type: "GET",
                    success: function (response) {
           
                        let dropdown = $("#customer_id"); // Select dropdown
                        dropdown.empty(); // Clear existing options
                        dropdown.append('<option value="">Select Customer</option>'); // Default option
                        // Loop through JSON array and add options
                        $.each(response.customers, function(index, customer) {
                            dropdown.append('<option value="' + customer.id + '">' + customer.name + '</option>');
                        });
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
            document.querySelectorAll('input[name="order_ids[]"]:checked').forEach(checkbox => {
                let row = checkbox.closest('tr');
                let orderData = {
                    id: row.cells[1].textContent,
                    customer_name: row.cells[2].textContent,
                    date: row.cells[3].textContent,
                    payment_type: row.cells[4].textContent,
                    amount: row.cells[5].textContent
                };
                selectedOrders.push(orderData);
            });

            if (selectedOrders.length > 0) {
                let modalBody = document.getElementById('modalBody');

                 // Create form dynamically inside the modal
                modalBody.innerHTML = `
                    <form id="selectedProductsForm" method="POST" action="{{ route('order.select') }}"  target="_blank">
                        @csrf
                        <ul>
                            ${selectedOrders.map(order => 
                                `<li>
                                    <strong>Order ID:</strong> ${order.id} 
                                    | <strong>Customer:</strong> ${order.customer_name} 
                                    | <strong>Date:</strong> ${order.date} 
                                    | <strong>Payment Type:</strong> ${order.payment_type} 
                                    | <strong>Amount:</strong> ${order.amount} 
                                    <input type="hidden" name="order_ids[]" value="${order.id}">
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

        // Handle Delete Selected Orders
        document.getElementById('actionForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission
            let selectedOrders = document.querySelectorAll('input[name="order_ids[]"]:checked');

            if (selectedOrders.length > 0) {
                if (confirm("Are you sure you want to delete selected orders?")) {
                    this.action = "{{ route('order.deletemultiple') }}";
                    this.submit();
                }
            } else {
                alert("No orders selected for deletion!");
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


    });
 
</script>


</body>
</x-app-layout>
</html>