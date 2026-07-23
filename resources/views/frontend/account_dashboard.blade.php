<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Account — FreshCrate</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
<style>
    :root { --pine: #1e3932; --bg-soft: #f9f9f9; --ink: #333; --ink-soft: #666; }
    body { font-family: 'Inter', sans-serif; background: #faf8f5; color: var(--ink); margin:0; }
    .dashboard-grid { display: grid; grid-template-columns: 280px 1fr; gap: 40px; margin-top: 40px; }
    .sidebar-card { background: white; padding: 24px; border-radius: 8px; border: 1px solid #eaeaea; height: fit-content; }
    .history-card { background: white; padding: 32px; border-radius: 8px; border: 1px solid #eaeaea; }
    .user-badge { width: 50px; height: 50px; background: #e8f5e9; color: var(--pine); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; }
    .history-table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 20px; }
    .history-table th { padding: 16px 12px; background: var(--bg-soft); color: var(--ink); font-weight: 600; border-bottom: 2px solid #eee; font-size: 0.9rem; }
    .history-table td { padding: 16px 12px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
    .status-pill { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-confirmed { background: #e2f0fd; color: #1d4ed8; }
    .status-completed { background: #d4edda; color: #155724; }
    .status-delivered { background: #d1ecf1; color: #0c5460; }
    .status-out-for-delivery { background: #ffdfd3; color: #c2410c; }
    .status-cancelled { background: #f8d7da; color: #721c24; }
    .btn-track { padding: 6px 12px; background: var(--pine); color: white; text-decoration: none; border-radius: 4px; font-size: 0.8rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px; transition: 0.2s; white-space: nowrap; }
    .btn-track:hover { opacity: 0.9; }
    .btn-cancel { padding: 6px 12px; background: #c92a2a; color: white; text-decoration: none; border-radius: 4px; font-size: 0.8rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px; transition: 0.2s; border: none; cursor: pointer; white-space: nowrap; }
    .btn-cancel:hover { opacity: 0.9; }
</style>
</head>
<body>

<header class="site-header" style="background:white; border-bottom: 1px solid #eee; padding: 15px 0;">
  <div class="wrap nav" style="display:flex; justify-content:space-between; align-items:center; max-width:1200px; margin:0 auto; padding:0 20px;">
    <a href="{{ route('home') }}" class="logo" style="font-family:'Anton', sans-serif; font-size:1.8rem; color:var(--pine); text-decoration:none;"><span class="mark"><i data-lucide="leaf"></i></span>FreshCrate</a>
    <a href="{{ route('home') }}" style="color:var(--ink-soft); text-decoration:none; font-size:0.95rem;">Back to Shop</a>
  </div>
</header>

<main class="wrap" style="max-width: 1200px; margin: 0 auto; padding: 0 20px 60px;">
    <div class="dashboard-grid">
        
        <!-- Sidebar Profile Info -->
        <aside class="sidebar-card">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
                <div class="user-badge">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <h3 style="margin:0; font-size:1.1rem;">{{ auth()->user()->name }}</h3>
                    <p style="margin:2px 0 0 0; font-size:0.85rem; color:var(--ink-soft);">Registered Customer</p>
                </div>
            </div>
            <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">
            <ul style="list-style:none; padding:0; margin:0; line-height: 2.5; font-size:0.95rem;">
                <li><a href="#" style="color:var(--pine); font-weight:600; text-decoration:none;">🛍️ Order History</a></li>
                <li>
                    <a href="{{ route('force.logout') }}" style="color:#c92a2a; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                        🚪 Logout Account
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Order History Area -->
        <section class="history-card">
            <h2 style="margin:0 0 8px 0; font-family:var(--font-heading); font-size:1.8rem;">Order History</h2>
            <p style="margin:0 0 24px 0; color:var(--ink-soft); font-size:0.95rem;">Check the status of your recent purchases and track shipments.</p>

            @if($orders->isEmpty())
                <div style="text-align:center; padding:40px 0; color:var(--ink-soft);">
                    <i data-lucide="shopping-bag" style="width:48px; height:48px; stroke-width:1.3; margin-bottom:12px;"></i>
                    <p>You haven't placed any orders yet.</p>
                </div>
            @else
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Cancel</th>
                            <th style="text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><strong style="font-family:var(--font-mono); font-size:0.9rem;">{{ $order->order_number }}</strong></td>
                            <td style="color:var(--ink-soft); font-size:0.9rem;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td style="font-weight:600;">Rs. {{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="status-pill status-{{ strtolower(str_replace(' ', '-', $order->status)) }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                @if(in_array(strtolower($order->status), ['pending', 'confirmed']))
                                    <form action="{{ route('order.cancel', $order->id) }}" method="POST" style="display:inline; margin:0;" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-cancel">
                                            <i data-lucide="x-circle" style="width:13px; height:13px;"></i> Cancel
                                        </button>
                                    </form>
                                @else
                                    <span style="color: var(--ink-soft); font-size: 0.85rem;">N/A</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('order.track', $order->order_number) }}" class="btn-track">
                                    <i data-lucide="compass" style="width:13px; height:13px;"></i> Track
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>

    </div>
</main>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>
</body>
</html>