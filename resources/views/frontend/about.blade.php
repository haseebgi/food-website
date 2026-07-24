<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About — FreshCrate</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
</head>
<body data-cart-count="{{ session('cart') ? count(session('cart')) : 0 }}">

<header class="site-header">
  <div class="strip"><div class="strip-track">
    <span>Free delivery over Rs. 2,000</span><span>Stamped fresh every morning</span><span>Same-day dispatch in-city</span><span>Farm-direct sourcing, no middlemen</span>
    <span>Free delivery over Rs. 2,000</span><span>Stamped fresh every morning</span><span>Same-day dispatch in-city</span><span>Farm-direct sourcing, no middlemen</span>
  </div></div>
  <div class="wrap nav">
    <a href="{{ route('home') }}" class="logo"><span class="mark"><i data-lucide="leaf"></i></span>FreshCrate</a>
    <nav class="nav-links">
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('shop') }}">Shop</a>
      <a href="{{ route('about') }}" class="active">About</a>
      <a href="{{ route('contact') }}">Contact</a>
    </nav>
    <div class="nav-actions">
      <a href="{{ route('account') }}" class="icon-btn" aria-label="Account"><i data-lucide="user"></i></a>
      <a href="{{ route('cart') }}" class="icon-btn" aria-label="Cart"><i data-lucide="shopping-basket"></i><span class="cart-count">3</span></a>
      <button class="nav-toggle icon-btn" aria-label="Menu" aria-expanded="false"><i data-lucide="menu"></i></button>
    </div>
  </div>
</header>

<div class="wrap crumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span>About</div>

<section class="page-head">
  <div class="wrap">
    <span class="eyebrow">Our story</span>
    <h1>Groceries, without the guesswork</h1>
    <p class="lede">FreshCrate started as a single spreadsheet tracking which suppliers had what in stock. It's grown into a full storefront — but the promise hasn't changed: you see exactly what's fresh, before you order.</p>
  </div>
</section>

<section style="padding-top:0;">
  <div class="wrap split">
    <div class="hero-art">
      <div class="crate" style="aspect-ratio:1/1;">
        <svg viewBox="0 0 120 120"><rect x="38" y="30" width="44" height="66" rx="8" fill="var(--paper)" stroke="var(--pine)" stroke-width="4"/><rect x="48" y="18" width="24" height="14" rx="3" fill="var(--pine)"/><rect x="38" y="58" width="44" height="38" fill="var(--citrus)"/></svg>
      </div>
    </div>
    <div>
      <span class="eyebrow">Why we exist</span>
      <h2>Built on real inventory, not guesswork</h2>
      <p>Most grocery apps show you a catalogue, not a stockroom. Ours is different: every listing is tied to live counts from our suppliers and our own inventory system, so a "Low Stock" badge means exactly that — order today or wait for the next batch.</p>
      <p>We work with a small, trusted set of local suppliers and growers rather than a sprawling network, which means shorter routes from harvest to your door and fresher produce at every step.</p>
      <div class="stat-row">
        <div class="stat"><b>2</b><span>Trusted suppliers</span></div>
        <div class="stat"><b>Same</b><span>Day dispatch</span></div>
        <div class="stat"><b>100%</b><span>Stock transparency</span></div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="section-head text-center" style="align-items:center;">
      <span class="eyebrow">What guides us</span>
      <h2>Our values</h2>
    </div>
    <div class="testi-row">
      <div class="card testi" style="text-align:left;">
        <span class="icon-circle" style="background:var(--cream); width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:50%; margin-bottom:14px; color:var(--pine);"><i data-lucide="eye"></i></span>
        <h3 style="margin-bottom:8px;">Transparent stock</h3>
        <p>If it's listed, it's counted. Low-stock badges update from the same system our team uses internally.</p>
      </div>
      <div class="card testi" style="text-align:left;">
        <span class="icon-circle" style="background:var(--cream); width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:50%; margin-bottom:14px; color:var(--pine);"><i data-lucide="handshake"></i></span>
        <h3 style="margin-bottom:8px;">Fair to suppliers</h3>
        <p>We work directly with local growers and dairies, cutting out markups that don't serve either side.</p>
      </div>
      <div class="card testi" style="text-align:left;">
        <span class="icon-circle" style="background:var(--cream); width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:50%; margin-bottom:14px; color:var(--pine);"><i data-lucide="clock"></i></span>
        <h3 style="margin-bottom:8px;">Same-day, always</h3>
        <p>Orders placed before 3pm are packed and dispatched the same afternoon — no next-day surprises.</p>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="cta-band">
      <div class="cta-band-inner">
        <div>
          <h2>Ready to fill your crate?</h2>
          <p>Browse today's stock and get it delivered before dinner.</p>
        </div>
        <a href="{{ route('shop') }}" class="btn btn-primary">Shop fresh produce <i data-lucide="arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>

<footer class="site-footer">
  <div class="wrap">
    <div class="footer-bottom" style="border-top:none; padding-top:0;">
      <span>© 2026 FreshCrate Grocers · Built on the Food Website platform</span>
      <span>Crated with care in Punjab</span>
    </div>
  </div>
</footer>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('public/js/main.js') }}"></script>
</body>
</html>
