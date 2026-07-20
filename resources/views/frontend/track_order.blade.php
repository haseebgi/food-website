<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Track Order #{{ $order->order_number }} — FreshCrate</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
{{-- ✅ Naya add hua - Leaflet map ke liye CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    :root { --pine: #1e3932; --bg-soft: #f9f9f9; --ink: #333; --ink-soft: #666; --accent: #e57373; }
    body { font-family: 'Inter', sans-serif; background: #faf8f5; color: var(--ink); margin:0; padding-bottom: 60px; }
    .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
    .card { background: white; padding: 32px; border-radius: 8px; border: 1px solid #eaeaea; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
    
    /* 🌟 Timeline Wrapper */
    .timeline-container { display: flex; justify-content: space-between; position: relative; margin: 40px 0; padding: 0 10px; }
    .timeline-line { position: absolute; top: 20px; left: 0; w_id_th: 100%; height: 4px; background: #e0e0e0; z-index: 1; }
    
    /* Dynamic Fill line based on status */
    .timeline-line-fill { position: absolute; top: 20px; left: 0; height: 4px; background: var(--pine); z-index: 2; transition: w_id_th 0.4s ease; }
    
    /* Status specific fills */
    @php
        $status = strtolower($order->status);
        $fillW_id_th = '0%';
        if($status == 'pending') $fillW_id_th = '15%';
        if($status == 'confirmed' || $status == 'order confirmed') $fillW_id_th = '50%';
        if($status == 'out for delivery') $fillW_id_th = '83%';
        if($status == 'delivered' || $status == 'completed') $fillW_id_th = '100%';
    @endphp
    
    .step { display: flex; flex-direction: column; align-items: center; z-index: 3; position: relative; w_id_th: 25%; }
    .step-icon { w_id_th: 44px; height: 44px; background: white; border: 3px solid #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #aaa; transition: 0.3s; background: white; }
    .step.active .step-icon { border-color: var(--pine); background: var(--pine); color: white; }
    .step.completed .step-icon { border-color: var(--pine); background: white; color: var(--pine); }
    .step-label { margin-top: 12px; font-size: 0.85rem; font-weight: 600; color: #aaa; text-align: center; }
    .step.active .step-label, .step.completed .step-label { color: var(--ink); }
    
    .order-items-table { w_id_th: 100%; border-collapse: collapse; margin-top: 20px; }
    .order-items-table th { padding: 12px; background: #fdfcf9; border-bottom: 1px solid #eee; text-align: left; font-size: 0.9rem; }
    .order-items-table td { padding: 16px 12px; border-bottom: 1px solid #f5f5f5; font-size: 0.95rem; }
</style>
</head>
<body>

<header style="background:white; border-bottom: 1px solid #eee; padding: 15px 0;">
  <div style="display:flex; justify-content:space-between; align-items:center; max-width:800px; margin:0 auto; padding:0 20px;">
    <a href="{{ route('account') }}" style="color:var(--pine); text-decoration:none; font-size:0.95rem; font-weight:500;">← Back to Dashboard</a>
    <span style="font-family:'Anton', sans-serif; font-size:1.5rem; color:var(--pine);">FreshCrate</span>
  </div>
</header>

<div class="container">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:baseline; border-bottom:1px solid #eee; padding-bottom:16px;">
            <div>
                <h2 style="margin:0; font-size:1.5rem;">Track Order</h2>
                <p style="margin:4px 0 0 0; color:var(--ink-soft); font-size:0.9rem;">Order ID: <strong>{{ $order->order_number }}</strong></p>
            </div>
            <div style="text-align:right;">
                <p style="margin:0; font-size:0.9rem; color:var(--ink-soft);">Placed on: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- 🚀 Live Dynamic Visual Timeline -->
        <div class="timeline-container">
            <div class="timeline-line"></div>
            <div class="timeline-line-fill" style="width: {{ $fillW_id_th }};"></div>

            <!-- Step 1: Pending -->
            <div class="step {{ in_array($status, ['pending', 'confirmed', 'order confirmed', 'out for delivery', 'delivered', 'completed']) ? ($status == 'pending' ? 'active' : 'completed') : '' }}">
                <div class="step-icon"><i data-lucide="clock"></i></div>
                <div class="step-label">Pending</div>
            </div>

            <!-- Step 2: Confirmed -->
            <div class="step {{ in_array($status, ['confirmed', 'order confirmed', 'out for delivery', 'delivered', 'completed']) ? (in_array($status, ['confirmed', 'order confirmed']) ? 'active' : 'completed') : '' }}">
                <div class="step-icon"><i data-lucide="check-circle"></i></div>
                <div class="step-label">Confirmed</div>
            </div>

            <!-- Step 3: Out for Delivery -->
            <div class="step {{ in_array($status, ['out for delivery', 'delivered', 'completed']) ? ($status == 'out for delivery' ? 'active' : 'completed') : '' }}">
                <div class="step-icon"><i data-lucide="truck"></i></div>
                <div class="step-label">Out for Delivery</div>
            </div>

            <!-- Step 4: Delivered -->
            <div class="step {{ in_array($status, ['delivered', 'completed']) ? 'active' : '' }}">
                <div class="step-icon"><i data-lucide="package"></i></div>
                <div class="step-label">Delivered</div>
            </div>
        </div>

        <!-- Order Summary Section -->
        <div style="margin-top: 40px;">
            <h3 style="margin:0 0 12px 0; font-size:1.1rem;">Items Summary</h3>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Grocery Item' }}</td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right; font-weight:600;">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr style="background:#faf8f5;">
                        <td colspan="2" style="text-align:right; font-weight:700; padding:12px;">Grand Total:</td>
                        <td style="text-align:right; font-weight:700; color:var(--pine); padding:12px;">Rs. {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ✅ ================================================================
         NAYA ADD HUA - Live Delivery Tracking Map
         ================================================================ --}}
    <div class="card" style="margin-top: 24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="margin:0; font-size:1.1rem;">Delivery Tracking</h3>
            <div id="rider-status" style="display:flex; align-items:center; gap:6px; font-size:0.85rem; color:var(--ink-soft);">
                <span id="status-dot" style="width:8px; height:8px; border-radius:50%; background:#ccc; display:inline-block;"></span>
                <span id="status-text">Connecting...</span>
            </div>
        </div>

        <div id="map" style="height: 350px; border-radius: 8px;"></div>
    </div>
    {{-- ✅ NAYE HISSE KA END --}}

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>

{{-- ✅ Naya add hua - Leaflet map JS aur live tracking script --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const locationUrl = @json(route('order.location', $order->order_number));
    const statusDot = document.getElementById('status-dot');
    const statusText = document.getElementById('status-text');

    // Default center (jab tak rider ki location na mile) - apni city ke coordinates daal do
    let map = L.map('map').setView([32.0836, 72.6711], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    const riderIcon = L.divIcon({
        html: '🛵',
        className: 'rider-marker',
        iconSize: [30, 30],
    });

    let riderMarker = null;

    function updateMap(lat, lng) {
        if (!riderMarker) {
            riderMarker = L.marker([lat, lng], { icon: riderIcon }).addTo(map);
            map.setView([lat, lng], 15);
        } else {
            riderMarker.setLatLng([lat, lng]);
        }
    }

    function pollLocation() {
        fetch(locationUrl)
            .then(res => res.json())
            .then(data => {
                if (!data.available) {
                    statusDot.style.background = '#ccc';
                    statusText.textContent = 'Waiting for rider';
                    return;
                }

                updateMap(data.lat, data.lng);

                if (data.is_stale) {
                    statusDot.style.background = '#e0a458';
                    statusText.textContent = 'Last seen ' + data.updated_at;
                } else {
                    statusDot.style.background = '#3fa34d';
                    statusText.textContent = 'Live — updated ' + data.updated_at;
                }
            })
            .catch(() => {
                statusDot.style.background = '#ccc';
                statusText.textContent = 'Connection issue';
            });
    }

    pollLocation();
    setInterval(pollLocation, 5000);
})();
</script>

</body>
</html>
