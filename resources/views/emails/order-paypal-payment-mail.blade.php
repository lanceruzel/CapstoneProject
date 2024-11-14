<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 10px;
        }
        .content {
            padding: 20px;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Order Payment Received</h2>
        </div>
        <div class="content">
            <p>Dear {{ $order->user->name() }},</p>

            <p>We are pleased to confirm that we have successfully received your payment for Order ID <strong>{{ $order->id }}</strong> via PayPal.</p>

            <p>Here are the details of your transaction:</p>
            <ul>
                <li><strong>Order ID:</strong> {{ $order->id }}</li>
                <li><strong>Amount Paid:</strong> ${{ number_format($order->total, 2) }}</li>
                <li><strong>Payment Method:</strong> PayPal</li>
                <li><strong>Reference Number:</strong> {{ $order->referenceNumber }}</li>
            </ul>

            <p>Here are the details of the products you ordered:</p>

            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderedItems as $orderedItem)
                        <tr>
                            <td>{{ $orderedItem->product->name }}</td>
                            <td>{{ $orderedItem->variation }}</td>
                            <td>x{{ $orderedItem->quantity }}</td>
                            <td>${{ number_format($orderedItem->price, 2) }}</td>
                            <td>${{ number_format($orderedItem->subtotal, 2) }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Subtotal:</strong></td>
                        <td>${{ number_format($order->orderedItems->sum('subtotal'), 2) }}</td>
                    </tr>
    
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Affiliate Discount (%{{ $order->discount_percentage }}):</strong></td>
                        <td>-${{ $order->discount }}</td>
                    </tr>

                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Shipping Fee:</strong></td>
                        <td>$3.00</td>
                    </tr>
    
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                        <td>${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <p>Your order is now being reviewed and will be processed and shipped soon. You can track your order status in your account under the <strong>Orders</strong> section.</p>

            <p>If you have any questions or need further assistance, please feel free to reach out to our support team at support@globeconnect.com.</p>

            <p>Thank you for shopping with us!<br>GlobeConnect</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
