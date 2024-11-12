<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Unsuspension Notice</title>
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
            <h2>Product Unsuspension Notice</h2>
        </div>
        <div class="content">
            <p>Dear {{ $seller }},</p>

            <p>We are pleased to inform you that the following product associated with your account have been unsuspended:</p>

            <ul>
                <li>
                    <strong>{{ $name }}</strong>
                </li>
            </ul>

            <p>If you have any further questions or require assistance, please feel free to contact our support team at support@globeconnect.com.</p>

            <p>Thank you for your attention.<br>Best regards,<br>GlobeConnect Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
