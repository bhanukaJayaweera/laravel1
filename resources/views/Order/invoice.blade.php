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
    
    <p><strong>Cashier:</strong> {{ $order->cashier_name }}</p>
    <p><strong>Invoice Date & Time:</strong> {{ $order->created_at }}</p>
    <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
    <p><strong>Delivery Date:</strong> {{ $order->date }}</p>
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
            <th>After Discount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->products as $index => $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->pivot->quantity }}</td>
            <td>{{ $product->currentMarketPrice(1)->first()->price ?? 'N/A' }}</td>
            <td>{{ number_format($product->pivot->quantity * $product->currentMarketPrice(1)->first()->price, 2) }}</td>
            <td>
                @if(isset($products[$index]['discount']))
                    {{ number_format((float)$products[$index]['discount'],2)}}
                @else
                    0.00
                @endif
            </td>
            <td>
                @if(isset($products[$index]['discount']))
                    {{ number_format(($product->pivot->quantity * $product->currentMarketPrice(1)->first()->price - (float)$products[$index]['discount']),2)}}
                @else
                    {{ number_format($product->pivot->quantity * $product->currentMarketPrice(1)->first()->price, 2) }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    <div style="text-align: right; margin-top: 30px;">
        <p><strong>Total Before Discount:</strong> {{ number_format(($totaldiscount+$order->amount),2) }}</p>
    </div>
    <div style="text-align: right; margin-top: 30px;">
        <p><strong>Total Discount:</strong> {{ number_format($totaldiscount,2) }}</p>
    </div>

    <div style="text-align: right; margin-top: 30px;">
        <p><strong>Grand Total:</strong> {{ number_format($order->amount,2) }}</p>
    </div>
    
    <hr style="margin-top: 50px;">
    <table>
    <thead>
        <tr>
            <th>Promotion</th>
            <th>Discount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
           @php
                $promotion = $product['promotion'] ?? 'No promotion';
                $hideRow = ($promotion === 'No promotion' && empty($product['discount']));
            @endphp
            
        @unless($hideRow)
        <tr>
            <td>      
                @isset($product['promotion'])
                    @if(!empty($product['promotion']))
                        {{ $product['promotion'] }}
                    @else
                        No promotion
                    @endif
                @else
                    No promotion
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
         @endunless
        @endforeach
    </tbody>
    </table>

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
