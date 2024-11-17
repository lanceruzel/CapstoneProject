<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Account Deactivation</title>
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
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Affiliate Program Update</h2>
        </div>
        <div class="content">
            <p>Dear {{ $promoterName }},</p>

            <p>We regret to inform you that your affiliate account with <strong>{{ $storeName }}</strong> has been deactivated.</p>

            <p>This decision was made after careful consideration and aligns with our affiliate program policies. While we appreciate your efforts as an affiliate, this action was necessary due to one or more of the following reasons:</p>
            
            <ul>
                <li>Violation of affiliate program terms and conditions.</li>
                <li>Inactive participation over an extended period.</li>
                <li>Other relevant factors as outlined in our program policies.</li>
            </ul>

            <p>If you believe this action was taken in error or have any questions, please feel free to contact our support team at affiliate@globeconnect.com for clarification or further assistance. Or you may directly contact {{ $storeName }} at {{ $storeEmail }}</p>

            <p>Thank you for your understanding, and we wish you all the best in your future endeavors.</p>

            <p>Best regards,<br>GlobeConnect Affiliate Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
