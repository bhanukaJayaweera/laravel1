<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        /* #total{
            margin: left 50px;
        } */
    </style>
</head>
<body>
    <h2>Invoice #{{ $order->id }}</h2>
    <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
    <p><strong>Date:</strong> {{ $order->date }}</p>
    <p><strong>Payment Type:</strong> {{ $order->payment_type }}</p>

    <h3>Products</h3>
    
    <table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Sub Total</th>
            <th>Discount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->products as $index => $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->pivot->quantity }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ number_format($product->pivot->quantity * $product->price, 2) }}</td>
            <td>
                @if(isset($products[$index]['discount']))
                    {{ number_format((float)$products[$index]['discount'],2)}}
                @else
                    0.00
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th>Promotion</th>
            <th>Discount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>      
                @isset($product['promotion'])
                @if(!empty($product['promotion']))
                    {{ $product['promotion'] }}
                @endif
                @endisset
            </td>
            <td>
                @isset($product['discount']) 
                @if((float)$product['discount'] != 0)
                    {{ number_format((float)$product['discount'],2)}}
                @endif
                @endisset
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    <div style="text-align: right; margin-top: 30px;">
        <p><strong>Total Discount:</strong> {{ number_format($totaldiscount,2) }}</p>
    </div>

    <div style="text-align: right; margin-top: 30px;">
        <p><strong>Grand Total:</strong> {{ number_format($order->amount,2) }}</p>
    </div>
    
    <hr style="margin-top: 50px;">

    <table style="width: 100%; margin-top: 80px;">
        <tr>
            <td style="text-align: center;">
                ___________________________<br>
                <strong>Customer Signature</strong>
            </td>
            <td style="text-align: center;">
                ___________________________<br>
                <strong>Cashier Signature</strong>
            </td>
        </tr>
    </table>
</body>
</html>
