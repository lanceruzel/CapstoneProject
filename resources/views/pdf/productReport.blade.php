<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Product Report Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header, .section, .footer {
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            margin: 5px 0;
        }
        .section h2 {
            font-size: 20px;
            border-bottom: 2px solid #333;
            margin-bottom: 10px;
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
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>Product Report Summary</h1>
        <p><strong>Date: </strong>{{ date('M d, Y', time()) }}</p>
        <p><strong>Time: </strong>{{ date('h:i a', time()) }}</p>
        {{-- <p><strong>Admin:</strong> John Doe</p> --}}
    </div>

    <!-- Product Information Section -->
    <div class="section">
        <h2>Product Information</h2>
        <div class="info">
            <p><strong>Products: </strong>
                @foreach(json_decode($report->products) as $items)
                    <p>{{ '(#'. $items->id .') ' . $items->name }}
                        @if(!$loop->last),@endif
                    </p>
                @endforeach
            </p>
            <p></p>
            <p><strong>Seller Name: </strong>{{ $report->seller->name() }}</p>
            <p><strong>Seller ID: </strong>#{{ $report->seller->id }}</p>
        </div>
    </div>

    <!-- Report Details Section -->
    <div class="section">
        <h2>Report Details</h2>
        <div class="info">
            <p><strong>Report ID: </strong>#{{ $report->id }}</p>
            <p><strong>Report Date: </strong>{{ date('M d, Y', strtotime($report->created_at)) }}</p>
            <p><strong>User: </strong>{{ $report->reporter->name() }}</p>
            <p><strong>User ID: </strong>#{{ $report->reporter->id }}</p>
            <p><strong>Reason: </strong>{{ $report->reason }}</p>
            <p><strong>Complaint Description: </strong>{{ $report->description }}</p>
            {{-- <p><strong>Action Taken: </strong>Product Suspended</p> --}}
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Confidential - For Internal Use Only</p>
    </div>

</body>
</html>
