<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact — FreshCrate</title>
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
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('contact') }}" class="active">Contact</a>
    </nav>
    <div class="nav-actions">
      <a href="{{ route('account') }}" class="icon-btn" aria-label="Account"><i data-lucide="user"></i></a>
      <a href="{{ route('cart') }}" class="icon-btn" aria-label="Cart"><i data-lucide="shopping-basket"></i><span class="cart-count">3</span></a>
      <button class="nav-toggle icon-btn" aria-label="Menu" aria-expanded="false"><i data-lucide="menu"></i></button>
    </div>
  </div>
</header>

<div class="wrap crumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span>Contact</div>

<section class="page-head">
  <div class="wrap">
    <span class="eyebrow">We're around</span>
    <h1>Get in touch</h1>
    <p class="lede">Questions about an order, a supplier partnership, or just want to say the eggs were great — reach us any of these ways.</p>
  </div>
</section>

<section style="padding-top:0;">
  <div class="wrap contact-grid">

    <div class="contact-info">
      <div class="item">
        <span class="icon-circle"><i data-lucide="map-pin"></i></span>
        <div><h3 style="font-size:1rem;">Depot address</h3><p>Model Town Road, Sargodha, Punjab, Pakistan</p></div>
      </div>
      <div class="item">
        <span class="icon-circle"><i data-lucide="phone"></i></span>
        <div><h3 style="font-size:1rem;">Phone / WhatsApp</h3><p>+92 300 1234567</p></div>
      </div>
      <div class="item">
        <span class="icon-circle"><i data-lucide="mail"></i></span>
        <div><h3 style="font-size:1rem;">Email</h3><p>hello@freshcrate.pk</p></div>
      </div>
      <div class="item">
        <span class="icon-circle"><i data-lucide="clock"></i></span>
        <div><h3 style="font-size:1rem;">Order hours</h3><p>Mon – Sat, 8:00am – 8:00pm · Orders before 3pm dispatch same day</p></div>
      </div>
      <div class="social-row" style="margin-top:8px;">
        <a href="#" aria-label="Facebook" style="border-color:var(--line-strong); color:var(--pine);"><i data-lucide="facebook"></i></a>
        <a href="#" aria-label="Instagram" style="border-color:var(--line-strong); color:var(--pine);"><i data-lucide="instagram"></i></a>
        <a href="#" aria-label="Twitter" style="border-color:var(--line-strong); color:var(--pine);"><i data-lucide="twitter"></i></a>
      </div>
    </div>

    <div class="card" style="padding:32px;">
      <h3 style="margin-bottom:20px;">Send a message</h3>
      <form data-demo-submit="Message sent — we'll reply within one business day.">
        <div class="field-row">
          <div class="field"><label>Name</label><input type="text" placeholder="Your name" required></div>
          <div class="field"><label>Email</label><input type="email" placeholder="you@example.com" required></div>
        </div>
        <div class="field">
          <label>Subject</label>
          <select required>
            <option value="">Choose a topic</option>
            <option>Order enquiry</option>
            <option>Supplier partnership</option>
            <option>Feedback</option>
            <option>Other</option>
          </select>
        </div>
        <div class="field"><label>Message</label><textarea rows="5" placeholder="How can we help?" required></textarea></div>
        <button type="submit" class="btn btn-primary btn-block">Send message <i data-lucide="send"></i></button>
      </form>
    </div>

  </div>
</section>

<footer class="site-footer" style="margin-top:64px;">
  <div class="wrap">
    <div class="footer-bottom" style="border-top:none; padding-top:0;">
      <span>© 2026 FreshCrate Grocers · Built on the Food Website platform</span>
      <span>Crated with care in Punjab</span>
    </div>
  </div>
</footer>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
