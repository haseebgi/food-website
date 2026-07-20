@extends('layouts.admin')

@section('title', 'Rider QR — Order #' . $order->order_number)

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Rider Tracking QR</h2>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card">
        <div class="card-body text-center" style="padding: 48px;">

            <p style="font-size: 0.95rem; color: #666; margin-bottom: 4px;">Order</p>
            <h4 style="margin-bottom: 24px;">{{ $order->order_number }}</h4>

            {{-- ✅ Free QR code service - koi API key ya card ki zarurat nahi --}}
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode($riderUrl) }}"
                 alt="Rider Tracking QR Code"
                 style="border: 1px solid #eee; border-radius: 8px; padding: 12px; background: white;">

            <p style="margin-top: 20px; font-size: 0.85rem; color: #888;">
                Rider apne phone ka camera khol ke ye QR scan kare — seedha location-sharing page khul jayega.
            </p>

            <div style="margin-top: 16px;">
                <input type="text" readonly value="{{ $riderUrl }}"
                       id="riderUrlInput"
                       style="width: 100%; max-width: 500px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-family: monospace; font-size: 0.85rem; text-align: center;">
            </div>

            <button onclick="copyRiderLink()" class="btn btn-outline-secondary btn-sm mt-2">
                Copy Link
            </button>

            <p id="copyMsg" style="color: green; font-size: 0.85rem; margin-top: 8px; display: none;">
                ✅ Link copy ho gaya!
            </p>

        </div>
    </div>

</div>

<script>
function copyRiderLink() {
    const input = document.getElementById('riderUrlInput');
    input.select();
    navigator.clipboard.writeText(input.value).then(() => {
        const msg = document.getElementById('copyMsg');
        msg.style.display = 'block';
        setTimeout(() => msg.style.display = 'none', 2000);
    });
}
</script>

@endsection
