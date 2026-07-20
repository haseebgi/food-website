<!DOCTYPE html>
<html>
<head>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>All Orders List</h2>
    <table>
        <thead>
            <tr>
                <th>Order No</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer->name ?? 'Walk-in' }}</td>
                <td>Rs. {{ $order->total_amount }}</td>
                <td>{{ $order->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>