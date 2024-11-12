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
            <h2>Return Request Declined</h2>
        </div>
        <div class="content">
            <p>Dear {{ $report->reporter->name() }},</p>

            <p>We have reviewed your return request for Order ID <strong>{{ $report->order_id }}</strong> and, after careful consideration, we have decided to decline the request. The reason for this decision is as follows:</p>

            <p><strong>Reason:</strong> {{ $reason }}</p>

            <p>If you have any questions or need further assistance, please feel free to reach out to our support team through support@globeconnect.com</p>

            <p>Best regards,<br>GlobeConnect</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
