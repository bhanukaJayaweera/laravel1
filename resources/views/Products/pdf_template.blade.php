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
    </style>
</head>
<body>
    <div class="container">
        <h2>Viewed Product Data</h2>
        <!-- <p><strong>Name:</strong> {{ $data['name'] ?? 'N/A' }}</p>
        <p><strong>Quantity:</strong> {{ $data['quantity'] ?? 'N/A' }}</p>
        <p><strong>Price:</strong> {{ $data['price'] ?? 'N/A' }}</p> -->
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody class="table-group-divider">
            
                <tr>
                    <td>{{ $data['id'] ?? 'N/A' }}</td>
                    <td>{{ $data['name'] ?? 'N/A' }}</td>
                    <td>{{ $data['quantity'] ?? 'N/A' }}</td>
                    <td>{{ $data['price'] ?? 'N/A' }}</td>
                </tr>
            
        </tody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>