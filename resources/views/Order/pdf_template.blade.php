<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>PDF Document</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }
        h2 { text-align: center; }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black; /* Ensure borders are visible */
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Viewed Orders Data</h2>
        <!-- <p><strong>Name:</strong> {{ $data['name'] ?? 'N/A' }}</p>
        <p><strong>Quantity:</strong> {{ $data['quantity'] ?? 'N/A' }}</p>
        <p><strong>Price:</strong> {{ $data['price'] ?? 'N/A' }}</p> -->
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Date</th>
                <th>Payment Type</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody class="table-group-divider">
            
            {{-- <tr>
                    <td>{{ $data['id'] ?? 'N/A' }}</td>
                    <td>{{ $data['name'] ?? 'N/A' }}</td>
                    <td>{{ $data['quantity'] ?? 'N/A' }}</td>
                    <td>{{ $data['price'] ?? 'N/A' }}</td>
                </tr> --}}
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer_id }}</td>
                    <td>{{$order->customer->name}}</td>
                    <td>{{ $order->product_id }}</td>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ $order->date }}</td>
                    <td>{{ $order->payment_type }}</td>
                    <td>{{ $order->amount }}</td>
                    
                </tr>
                @endforeach
                
        </tody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>