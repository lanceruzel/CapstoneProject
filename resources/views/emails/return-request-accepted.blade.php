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
            <h2>Return Request Accepted</h2>
        </div>
        <div class="content">
            <p>Dear {{ $report->reporter->name() }},</p>

            <p>We are writing to inform you that your return request for Order ID <strong>{{ $report->order_id }}</strong> has been accepted. We appreciate your patience and cooperation throughout this process.</p>

            <p>Please follow the instructions below to complete your return:</p>

            <ul>
                <li>Securely package the item for return shipping.</li>
                <li>Ship the item to the return address provided (or use the return label if one has been sent).</li>
                <li>Once the item has been shipped, please enter the tracking number in your account under the <strong>Orders</strong> section, within the <strong>Return</strong> tab.</li>
            </ul>

            <p>If you have any questions or need further assistance, please feel free to reach out to our support team through support@globeconnect.com</p>

            <p>Best regards,<br>GlobeConnect</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
