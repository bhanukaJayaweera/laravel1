<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <p><strong>Name:</strong> {{ $data['name'] ?? 'N/A' }}</p>
        <p><strong>Quantity:</strong> {{ $data['quantity'] ?? 'N/A' }}</p>
        <p><strong>Price:</strong> {{ $data['price'] ?? 'N/A' }}</p>
    </div>
</body>
</html>