<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled - Refund Processed</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Order Cancelled</h2>
        </div>
        <div class="content">
            <p>Dear {{ $order->name }},</p>

            <p>We confirm that your recent order with Order ID <strong>{{ $order->id }}</strong> has been successfully cancelled as per your request. A refund of <strong>${{ number_format($order->total, 2) }}</strong> has been processed to your original payment method.</p>

            <p>If you have any questions or need further assistance, please feel free to reach out to our support team through support@globeconnect.com.</p>

            <p>Best regards,<br>GlobeConnect</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
