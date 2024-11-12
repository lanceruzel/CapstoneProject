<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Suspension Notice</title>
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
            <h2>Product Suspension Notice</h2>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>

            <p>We are reaching out to inform you that the following products associated with your account have been suspended:</p>

            <ul>
                @foreach ($products as $product)
                    <li><strong>{{ $product }}</strong></li>
                @endforeach
            </ul>

            <p><strong>Reason for Suspension:</strong> {{ $reason }}</p>

            <p>If you believe the suspension was made in error or wish to appeal the decision, you may do so by following these steps:</p>
            <ul>
                <li>Visit the <strong>Store Management</strong> section on your account.</li>
                <li>Go to <strong>Product Management</strong>.</li>
                <li>Select the suspended products and choose the option to <strong>Make an Appeal</strong> for each item.</li>
            </ul>

            <p>Once your appeal has been reviewed, you will be notified of the outcome.</p>

            <p>If you have any questions or require assistance, please feel free to contact our support team at support@globeconnect.com.</p>

            <p>Thank you for your attention to this matter.<br>Best regards,<br>GlobeConnect Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
