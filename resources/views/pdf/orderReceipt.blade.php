<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        .header, .section, .footer {
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
        }
        .section h2 {
            font-size: 22px;
            border-bottom: 2px solid #333;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .section p {
            margin: 5px 0;
        }
        .section .info {
            margin-bottom: 15px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>Invoice</h1>
        <p><strong>Date: </strong>{{ date('M d, Y', time()) }}</p>
        <p><strong>Time: </strong>{{ date('h:i a', time()) }}</p>
    </div>

    <!-- Customer Information Section -->
    <div class="section">
        <h2>Seller Information Information</h2>
        <div class="info">
            <p><strong>Store: </strong>{{ $order->seller->name() }}</p>
            <p><strong>Address: </strong>{{ $order->seller->storeInformation->country . ' ' . $order->seller->storeInformation->address }}</p>
            <p><strong>Contact: </strong>{{ $order->seller->storeInformation->contact }}</p>
            <p><strong>Email: </strong>{{ $order->seller->storeInformation->email }}</p>
        </div>
    </div>

    <!-- Buyer Information Section -->
    <div class="section">
        <h2>Buyer Information</h2>
        <div class="info">
            <p><strong>Buyer Name: </strong>{{ $order->name }}</p>
            <p><strong>Address: </strong>{{ $order->address }}</p>
            <p><strong>Contact: </strong>{{ $order->contact }}</p>
        </div>
    </div>

    <!-- Order Details Section -->
    <div class="section">
        <h2>Order Details</h2>
        <div class="info">
            <p><strong>Order ID: </strong>#{{ $order->id }}</p>
            <p><strong>Order Date: </strong>{{ date('M d, Y', strtotime($order->created_at)) }}</p>
            <p><strong>Payment Method: </strong>{{ $order->payment_method == 'COD' ? 'Cash on delivery' : $order->payment_method }}</p>
            @if($order->referenceNumber)
                <p><strong>Reference Number: </strong>{{ $order->referenceNumber }}</p>
            @endif
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Variation</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderedItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->variation }}</td>
                        <td>x{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Subtotal:</strong></td>
                    <td>${{ number_format($order->orderedItems->sum('subtotal'), 2) }}</td>
                </tr>

                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Shipping Fee:</strong></td>
                    <td>$25.00</td>
                </tr>

                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Affiliate Discount (%{{ $order->discount_percentage }}):</strong></td>
                    <td>-${{ $order->discount }}</td>
                </tr>

                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                    <td>${{ number_format($order->total, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
