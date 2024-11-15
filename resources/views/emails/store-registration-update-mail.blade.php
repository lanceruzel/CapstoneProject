<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Registration Updated</title>
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
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Store Registration Updated</h2>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>

            <p>We’re pleased to inform you that your store registration details have been successfully updated. You can log in to your account to review the changes and manage your store.</p>

            <p>To check the updated information, please click the button below:</p>

            <p>
                <a href="{{ url('/') }}" class="button">View Your Store</a>
            </p>

            <p>If you have any questions or need assistance, feel free to contact us at support@globeconnect.com.</p>

            <p>Best regards,<br>GlobeConnect Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
