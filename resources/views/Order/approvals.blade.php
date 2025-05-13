@can('approve orders')
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order Update/Delete Approvals
        </h2>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <!-- Font Awesome CDN (Add to <head> section) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    </x-slot>

  
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
                    <p id="d" style="display: none;"></p>
                    <p id="derror" style="display: none;">Error deleting Order</p>
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                       
                    </div>
                
            </div>
        </div>
    </div>
       <!-- Modal view-->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalTitle">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <!-- <form id="orderForm"> -->
            <!-- @csrf   -->
            <div class="input-group mb-3" hidden>
                <label class="input-group-text">Order ID</label>
                <input type="text" name="id" id="id" class="form-control">
            </div> 
            <input type="hidden" name="request_id" id="request_id" class="form-control">

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
           
             <div class="input-group mb-3">
                <label class="input-group-text" id="inputGroup-sizing-default">Status</label>
                <div style="padding: 5px">
                    @php
                        $statuses = ['new', 'processing', 'completed', 'cancelled'];
                    @endphp

                    @foreach($statuses as $status)
                        <div class="form-check form-check-reverse">
                           
                            <input class="form-check-input" type="radio" 
                                id="status" 
                                name="status" 
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
                <button type="button" class="btn btn-primary approve" hidden>Approve Delete</button>
                <button type="button" class="btn btn-primary reject" hidden>Reject Delete</button>
                <button type="button" class="btn btn-primary approveUpdate" hidden>Approve Update</button>
                <button type="button" class="btn btn-primary rejectUpdate" hidden>Reject Update</button>

            </div>
        <!-- </form> -->
        </div>
    </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            <table class="table-auto w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">Request ID</th>
                            <th class="px-4 py-2 border">Order ID</th>
                            <th class="px-4 py-2 border">Requested By</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border"></th>
                            <th class="px-4 py-2 border"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $req)
                    <tr class="border">
                        <td class="px-4 py-2 border"> {{ $req->id }}</td>
                        <td class="px-4 py-2 border"> {{ $req->order->id ?? 'No Order Found' }}</td>
                        <td class="px-4 py-2 border">{{ $req->user->name }}</td>
                        <td class="px-4 py-2 border">{{ $req->status }}</td>
                        <!-- <td class="px-4 py-2 border">
                            <form method="POST" action="{{ route('order.approve', $req->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-success">Approve</button>
                            </form>
                        </td>
                        <td class="px-4 py-2 border">
                            <form method="POST" action="{{ route('order.reject', $req->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-danger">Reject</button>
                            </form>
                        </td> -->
                        <td class="px-4 py-2 border">
                            <button class="btn btn-info view-order-btn" data-id="{{ $req->order->id ?? ''}}"  data-request-id="{{ $req->id }}" data-status="{{ $req->status }}">View</button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>

            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  
    <!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>

// $(document).on('click', '.view-order-btn', function() {
$(document).ready(function () {
        //order-product Modal- add new product
    $(".view-order-btn").click(function () {
    var orderId = $(this).data('id');
    var requestId = $(this).data('request-id');
    var status = $(this).data('status');
    $('#request_id').val(requestId);
    
    var url = status == "Updated" 
        ? '/order/' + orderId + '/load' 
        : '/order/' + orderId + '/loaddelete';
    
    
    $.ajax({
        url: url,
        type: 'GET',
        data: { requestId: requestId },
        success: function(response) {
                if(status == "Updated"){
                    $(".approveUpdate").prop("hidden", false);
                    $(".rejectUpdate").prop("hidden", false);
                }
                if(status == "Deleted"){
                    $(".approve").prop("hidden", false);
                    $(".reject").prop("hidden", false);
                }
            // Common setup for both Updated and Deleted cases
            $("#id").val(response.order.id).prop("disabled", true);
            $("#product_div, #quantity_div, #addProductUpdate").prop("hidden", true);
            
            // Customer dropdown setup
            let dropdown = $("#customer_id");
            dropdown.empty().append('<option value="">Select Customer</option>');
            $.each(response.customers, function(index, customer) {
                dropdown.append('<option value="' + customer.id + '">' + customer.name + '</option>');
            });
            dropdown.val(response.order.customer_id).prop("disabled", true);
            
            // Products table setup
            let tableBody = $("#productTableBody");
            tableBody.empty();
            
            var products = status == "Updated" 
                ? response.requested_changes.products
                : response.order.products;
            
                $.each(products, function (index, product) {
                // Determine quantity field based on status
                var quantityCell = status == "Updated" 
                    ? `<td>${product.quantity}</td>` 
                    : `<td>${product.pivot.quantity}</td>`;
                
                tableBody.append(`
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        ${quantityCell}
                        <td>${product.price}</td>
                    </tr>
                `);
            });
            
            // Apply requested changes if status is "Updated"
            if (status == "Updated" && response.requested_changes) {
                let changes = response.requested_changes;
                let order = response.order;
                
                if (changes.customer_id) order.customer_id = changes.customer_id;
                if (changes.payment_type) order.payment_type = changes.payment_type;
                if (changes.amount) order.amount = changes.amount;
                if (changes.date) order.date = changes.date;
                if (changes.status) order.status = changes.status;
                
                // Update dropdown with potentially changed customer
                dropdown.val(order.customer_id);
            }
            
            // Set other form values
            $("#date").val(response.order.date).prop("disabled", true);
            $("#payment_type").val(response.order.payment_type).prop("disabled", true);
            $("#amount").val(response.order.amount).prop("disabled", true);
            $(`input[name="status"][value="${response.order.status}"]`)
                .prop('checked', true)
                .prop("disabled", true);
            
            $("#modalTitle").text("View Order - " + status);
            $("#orderModal").modal("show");
        },
        error: function(xhr) {
            console.error(xhr);
            alert("Error loading order details");
        }
    });
});


$('.approve').on('click', function () {
    console.log($('#request_id').val());
    var requestId = $('#request_id').val();

        $.ajax({
            url: '/order-approve/' + requestId, // or whatever route you use
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#orderModal").modal("hide"); // Close modal
                $('button[data-request-id="'+requestId+'"]').closest('tr').remove(); // Remove the table row
                $("#d").text(response.message).show();
                $("#messageModal").modal("show");
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function (xhr) {
                        //alert("Error saving order!");
                $("#orderModal").modal("hide"); // Close modal
                $("#derror").show();
                $("#messageModal").modal("show");
            },

        });
});

$('.reject').on('click', function () {
    console.log($('#request_id').val());
    var requestId = $('#request_id').val();

        $.ajax({
            url: '/order-reject/' + requestId, // or whatever route you use
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#orderModal").modal("hide"); // Close modal
                $('button[data-request-id="'+requestId+'"]').closest('tr').remove();
                $("#d").text(response.message).show();
                $("#messageModal").modal("show");
            },
            error: function (xhr) {
                        //alert("Error saving order!");
                $("#orderModal").modal("hide"); // Close modal
                $("#derror").show();
                $("#messageModal").modal("show");
            },

        });
});

$('.approveUpdate').on('click', function () {
    
    var requestId = $('#request_id').val();

        $.ajax({
            url: '/update-approve/' + requestId, // or whatever route you use
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#orderModal").modal("hide"); // Close modal
                $('button[data-request-id="'+requestId+'"]').closest('tr').remove(); // Remove the table row
                $("#d").text(response.message).show();
                $("#messageModal").modal("show");
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function (xhr) {
                        //alert("Error saving order!");
                $("#orderModal").modal("hide"); // Close modal
                $("#uerror").show();
                $("#messageModal").modal("show");
            },

        });
});
$('.rejectUpdate').on('click', function () {
    console.log($('#request_id').val());
    var requestId = $('#request_id').val();

        $.ajax({
            url: '/update-reject/' + requestId, // or whatever route you use
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#orderModal").modal("hide"); // Close modal
                $('button[data-request-id="'+requestId+'"]').closest('tr').remove();
                $("#d").text(response.message).show();
                $("#messageModal").modal("show");
            },
            error: function (xhr) {
                        //alert("Error saving order!");
                $("#orderModal").modal("hide"); // Close modal
                $("#uerror").show();
                $("#messageModal").modal("show");
            },

        });
});

});

//     $.ajax({
//         if(status == "Updated"){
//         url: '/order/' + orderId + '/load', // or whatever route you use
//         type: 'GET',
//         data: {
//             requestId: requestId,
//         },
//         success: function(response) {
//             //$("#orderForm")[0].reset(); // Clear Form
//                 let requestedChanges = response.requested_changes;
//                 let order = response.order;

//                 if (requestedChanges) {
//                     if (requestedChanges.customer_id) {
//                         order.customer_id = requestedChanges.customer_id;
//                     }
//                     if (requestedChanges.payment_type) {
//                         order.payment_type = requestedChanges.payment_type;
//                     }
//                     if (requestedChanges.products) {
//                         order.amount = requestedChanges.amount;
//                     }
//                     if (requestedChanges.products) {
//                         order.date = requestedChanges.date;
//                     }
//                     if (requestedChanges.products) {
//                         order.status = requestedChanges.status;
//                     }
//                 }
//                         $("#id").val(order.id);
//                         $("#id").prop("disabled", true);
//                         $("#product_div").prop("hidden", true);
//                         $("#quantity_div").prop("hidden", true);
//                         $("#addProductUpdate").prop("hidden", true);
//                         let dropdown = $("#customer_id"); // Select dropdown
//                         dropdown.empty(); // Clear existing options
//                         dropdown.append('<option value="">Select Customer</option>'); // Default option
//                         // Loop through JSON array and add options
//                         $.each(response.customers, function(index, customer) {
//                             dropdown.append('<option value="' + customer.id + '">' + customer.name + '</option>');
//                         });
//                         $("#customer_id").val(order.customer_id);
//                         $("#customer_id").prop("disabled", true);

//                         let tableBody = $("#productTableBody");
//                         tableBody.empty(); // Clear existing data

//                         $.each(requestedChanges.products, function (index, product) {
//                             //Log::info('Product:',$product);
//                             tableBody.append(`
//                                 <tr>
//                                     <td>${product.id}</td>
//                                     <td>${product.name}</td>
//                                     <td>${product.pivot.quantity}</td>
//                                     <td>${product.price}</td>
//                                 </tr>
//                             `);
//                         });
                        
//                         $("#date").val(order.date);
//                         $("#date").prop("disabled", true);
//                         $("#payment_type").val(order.payment_type);
//                         $("#payment_type").prop("disabled", true);
//                         $("#amount").val(order.amount);
//                         $("#amount").prop("disabled", true);
//                         $('input[name="status"]').prop('checked', false);
//                         // Select the radio that matches the status
//                         $(`input[name="status"][value="${order.status}"]`).prop('checked', true);
//                         $('input[name="status"]').prop("disabled", true);
//                         $("#modalTitle").text("View Order");
//                         //$(".save").prop("hidden", true);
//                         $("#orderModal").modal("show");
//         }
//     }
//     if(status == "Deleted"){
        
//         url: '/order/' + orderId + '/loaddelete', // or whatever route you use
//         type: 'GET',
//         data: {
//             requestId: requestId,
//         },
//         success: function(response) {
//                         $("#id").val(response.order.id);
//                         $("#id").prop("disabled", true);
//                         $("#product_div").prop("hidden", true);
//                         $("#quantity_div").prop("hidden", true);
//                         $("#addProductUpdate").prop("hidden", true);
//                         let dropdown = $("#customer_id"); // Select dropdown
//                         dropdown.empty(); // Clear existing options
//                         dropdown.append('<option value="">Select Customer</option>'); // Default option
//                         // Loop through JSON array and add options
//                         $.each(response.customers, function(index, customer) {
//                             dropdown.append('<option value="' + customer.id + '">' + customer.name + '</option>');
//                         });
//                         $("#customer_id").val(response.order.customer_id);
//                         $("#customer_id").prop("disabled", true);

//                         let tableBody = $("#productTableBody");
//                         tableBody.empty(); // Clear existing data

//                         $.each(response.products, function (index, product) {
//                             //Log::info('Product:',$product);
//                             tableBody.append(`
//                                 <tr>
//                                     <td>${product.id}</td>
//                                     <td>${product.name}</td>
//                                     <td>${product.pivot.quantity}</td>
//                                     <td>${product.price}</td>
//                                 </tr>
//                             `);
//                         });
                        
//                         $("#date").val(response.order.date);
//                         $("#date").prop("disabled", true);
//                         $("#payment_type").val(response.order.payment_type);
//                         $("#payment_type").prop("disabled", true);
//                         $("#amount").val(response.order.amount);
//                         $("#amount").prop("disabled", true);
//                         $('input[name="status"]').prop('checked', false);
//                         // Select the radio that matches the status
//                         $(`input[name="status"][value="${response.order.status}"]`).prop('checked', true);
//                         $('input[name="status"]').prop("disabled", true);
//                         $("#modalTitle").text("View Order");
//                         //$(".save").prop("hidden", true);
//                         $("#orderModal").modal("show");
//         }

//     }
//     });
// });
</script>
</x-app-layout>

@endcan
