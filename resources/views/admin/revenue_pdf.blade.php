<!DOCTYPE html>
<html>
<head>
    <title>Revenue Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary-box { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-box td { padding: 10px; border: 1px solid #ddd; background: #f9f9f9; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #eee; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background: #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Revenue Report</h1>
        <p>Generated on: {{ $generatedAt }}</p>
    </div>

    <h3>Summary Overview</h3>
    <table class="summary-box">
        <tr>
            <td><strong>Total Revenue:</strong> Rs. {{ number_format($totalPaidByCustomers, 2) }}</td>
            <td><strong>Total Base Cost:</strong> Rs. {{ number_format($totalBaseCost, 2) }}</td>
            <td><strong>Total Profit (Margin):</strong> Rs. {{ number_format($totalMargin, 2) }}</td>
        </tr>
    </table>

    <h3>Daily Breakdown</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th class="text-right">Daily Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyRevenue as $day)
            <tr>
                <td>{{ $day->date }}</td>
                <td class="text-right">Rs. {{ number_format($day->daily_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Order Details</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Items Count</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->unique_order_id ?? $order->id }}</td>
                <td>{{ $order->customer->name ?? 'Guest' }}</td>
                <td>{{ $order->items->count() }}</td>
                <td class="text-right">Rs. {{ number_format($order->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
