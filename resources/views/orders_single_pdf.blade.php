<h3>Order Details</h3>
<p>Order No: {{ $order->order_number }}</p>
<p>Customer: {{ $order->customer->name ?? 'Walk-in' }}</p>
<p>Total Amount: Rs. {{ $order->total_amount }}</p>
<p>Status: {{ $order->status }}</p>