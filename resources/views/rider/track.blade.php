<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Share Delivery Location — FreshCrate</title>
<style>
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: #f4f4f4;
        margin: 0;
        padding: 24px;
        text-align: center;
    }
    .card {
        background: white;
        border-radius: 12px;
        padding: 32px 24px;
        max-width: 420px;
        margin: 40px auto;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    h1 { font-size: 1.4rem; margin-bottom: 8px; }
    p.order-no { font-family: monospace; color: #555; margin-bottom: 24px; }
    #status {
        margin: 20px 0;
        padding: 14px;
        border-radius: 8px;
        font-weight: 500;
    }
    .status-idle { background: #f0f0f0; color: #555; }
    .status-sharing { background: #d4edda; color: #155724; }
    .status-error { background: #f8d7da; color: #721c24; }
    button {
        padding: 14px 28px;
        font-size: 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }
    #startBtn { background: #1e3932; color: white; }
    #stopBtn { background: #a94442; color: white; display: none; }
</style>
</head>
<body>

<div class="card">
    <h1>📍 Delivery Location Sharing</h1>
    <p class="order-no">Order: {{ $order->order_number }}</p>

    <div id="status" class="status-idle">
        Location sharing band hai. Shuru karne ke liye button dabao.
    </div>

    <button id="startBtn">Start Sharing Location</button>
    <button id="stopBtn">Stop Sharing</button>
</div>

<script>
    const orderNumber = @json($order->order_number);
    const updateUrl = @json(route('rider.track.update', $order->order_number));
    const csrfToken = @json(csrf_token());

    const statusDiv = document.getElementById('status');
    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');

    let watchId = null;
    let intervalId = null;
    let lastPosition = null;

    function setStatus(message, type) {
        statusDiv.textContent = message;
        statusDiv.className = 'status-' + type;
    }

    function sendLocation(lat, lng) {
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ lat: lat, lng: lng }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                setStatus('✅ Location bheji ja rahi hai (' + new Date().toLocaleTimeString() + ')', 'sharing');
            }
        })
        .catch(() => {
            setStatus('⚠️ Location bhejne mein masla, dobara koshish ho rahi hai...', 'error');
        });
    }

    function startSharing() {
        if (!navigator.geolocation) {
            setStatus('❌ Aapka browser location support nahi karta.', 'error');
            return;
        }

        setStatus('📡 Location le rahe hain...', 'idle');

        // Har baar jab GPS position change ho, save kar lo
        watchId = navigator.geolocation.watchPosition(
            (position) => {
                lastPosition = position.coords;
            },
            (error) => {
                setStatus('❌ Location access allow karein (browser permission).', 'error');
            },
            { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 }
        );

        // Har 8 second baad latest position server ko bhejo
        intervalId = setInterval(() => {
            if (lastPosition) {
                sendLocation(lastPosition.latitude, lastPosition.longitude);
            }
        }, 8000);

        startBtn.style.display = 'none';
        stopBtn.style.display = 'inline-block';
    }

    function stopSharing() {
        if (watchId !== null) navigator.geolocation.clearWatch(watchId);
        if (intervalId !== null) clearInterval(intervalId);

        setStatus('Location sharing band ho gayi.', 'idle');
        startBtn.style.display = 'inline-block';
        stopBtn.style.display = 'none';
    }

    startBtn.addEventListener('click', startSharing);
    stopBtn.addEventListener('click', stopSharing);
</script>

</body>
</html>
