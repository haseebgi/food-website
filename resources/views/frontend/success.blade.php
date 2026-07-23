<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Success — FreshCrate</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
</head>
<body>

<header class="site-header">
  <div class="wrap nav">
    <a href="{{ route('home') }}" class="logo"><span class="mark"><i data-lucide="leaf"></i></span>FreshCrate</a>
  </div>
</header>

<section style="padding: 60px 0; text-align: center;">
  <div class="wrap" style="max-width: 600px; margin: 0 auto;">
    <div class="card" style="padding: 40px; border-top: 5px solid var(--pine);">
      <div style="background: #e8f5e9; color: var(--pine); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
        <i data-lucide="check-circle-2" style="width: 32px; height: 32px;"></i>
      </div>
      
      <h1 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: 8px; color: var(--ink);">Thank You!</h1>
      <p class="lede" style="margin-bottom: 24px; color: var(--ink-soft);">Your order has been crated successfully and is on its way.</p>
      
      <div style="background: var(--bg-soft); padding: 16px; border-radius: 8px; font-family: var(--font-mono); font-size: 0.9rem; margin-bottom: 32px; border: 1px solid #eee;">
        Order Number: <strong style="color: var(--ink);">{{ $order_number }}</strong>
      </div>

      {{-- 💡 Flex layout ke andar dono buttons ko styling ke sath set kar diya ha --}}
      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('order.invoice', $order_number) }}" target="_blank" class="btn" style="padding: 12px 24px; background-color: #1e3932; color: white; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border-radius: 4px; font-weight: 500;">
          <i data-lucide="file-text" style="width: 18px; height: 18px;"></i> View & Print Bill
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline" style="padding: 12px 24px; display: inline-flex; align-items: center; text-decoration: none; border-radius: 4px;">
          Continue Shopping
        </a>
      </div>
    </div>
  </div>
</section>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>
</body>
</html>