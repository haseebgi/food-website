<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice — {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #333; margin: 0; padding: 20px; background-color: #f9f9f9; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; background: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); border-radius: 8px; }
        .invoice-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f4f4f4; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e3932; text-transform: uppercase; }
        .invoice-details { display: flex; justify-content: space-between; margin-bottom: 30px; font-size: 0.95rem; line-height: 1.5; }
        .table { width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 30px; }
        .table th { background-color: #f8f9fa; padding: 12px; font-weight: 600; border-bottom: 2px solid #dee2e6; }
        .table td { padding: 12px; border-bottom: 1px solid #eee; }
        .totals-section { width: 40%; margin-left: auto; font-size: 1rem; line-height: 2; }
        .totals-row { display: flex; justify-content: space-between; }
        .totals-row.grand-total { font-weight: bold; border-top: 1px solid #ddd; padding-top: 5px; font-size: 1.2rem; color: #1e3932; }
        .no-print-btn { display: block; width: fit-content; margin: 20px auto 0 auto; padding: 10px 20px; background-color: #1e3932; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; border: none; cursor: pointer; }
        
        /* Automatically hides buttons and background shadows during standard browser printing */
        @media print {
            body { background: white; padding: 0; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
            .no-print-btn { display: none; }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <!-- Header -->
    <div class="invoice-header">
        <div class="logo">FreshCrate</div>
        <div>
            <h2 style="margin: 0; color: #1e3932;">INVOICE</h2>
            <p style="margin: 5px 0 0 0; font-size: 0.9rem; color: #666;">Order #{{ $order->order_number }}</p>
        </div>
    </div>

    <!-- Details -->
    <div class="invoice-details">
        <div>
            <strong>Billing To:</strong><br>
            Name: {{ $order->name }}<br>
            Phone: {{ $order->phone }}<br>
            Email: {{ $order->email ?? 'N/A' }}
        </div>
        <div>
            <strong>Shipping Address:</strong><br>
            {{ $order->address }}<br>
            {{ $order->city }}, {{ $order->postal_code ?? '' }}<br>
            <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}
        </div>
    </div>

    <!-- Items Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Product Description</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    {{ $item->product->name ?? 'Product' }}
                    @if(!empty($item->size))
                        <span style="font-size: 0.8rem; color: #666;">({{ ucfirst($item->size) }})</span>
                    @endif
                </td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">Rs. {{ number_format($item->price, 2) }}</td>
                <td style="text-align: right;">Rs. {{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Totals -->
    <div class="totals-section">
        <div class="totals-row">
            <span>Subtotal:</span>
            <span>Rs. {{ number_format($order->total_amount - ($order->total_amount > 2000 ? 0 : 100), 2) }}</span>
        </div>
        <div class="totals-row">
            <span>Delivery Charge:</span>
            <span>Rs. {{ $order->total_amount > 2100 || ($order->total_amount - 100) > 2000 ? '0.00' : '100.00' }}</span>
        </div>
        <div class="totals-row grand-total">
            <span>Grand Total:</span>
            <span>Rs. {{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>
    
    <div style="margin-top: 40px; text-align: center; font-size: 0.85rem; color: #888;">
        Thank you for shopping with FreshCrate! Generated automatically on 2026.
    </div>

    <!-- Interactive Buttons -->
    <button class="no-print-btn" onclick="window.print()">Print This Invoice</button>
</div>

</body>
</html>