<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Program Invitation</title>
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
            <h2>Join Our Affiliate Program</h2>
        </div>
        <div class="content">
            <p>Dear {{ $affiliate->user->name() }},</p>

            <p>We are excited to invite you to become an affiliate of us {{ $affiliate->store->name() }}! As a promoter, you will have the opportunity to earn commissions by referring customers to our online store.</p>

            <p>Here’s how it works:</p>

            <ul>
                <li>Share your unique affiliate code with your audience.</li>
                <li>Earn a commission for every successful sale made through your affiliate code.</li>
                <li>Track your earnings and performance through your affiliate dashboard.</li>
            </ul>

            <p>You may want to check the further details on your affiliate dashboard under the invites.</p>

            <p>If you have any questions or need assistance, feel free to reach out to our affiliate support team at affiliate@globeconnect.com.</p>

            <p>We look forward to working with you and helping you succeed as an affiliate!</p>

            <p>Best regards,<br>GlobeConnect Affiliate Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} GlobeConnect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
