<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FreshCrate — Farm-Fresh Groceries, Delivered Today</title>
<meta name="description" content="FreshCrate is a farm-direct grocery storefront — fresh produce, dairy and pantry staples, stamped fresh and dispatched same-day.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
</head>
<h2>thi is</h2>
<body data-cart-count="0">

<header class="site-header">
  <div class="strip"><div class="strip-track">
    <span>Free delivery over Rs. 2,000</span><span>Stamped fresh every morning</span><span>Same-day dispatch in-city</span><span>Farm-direct sourcing, no middlemen</span>
    <span>Free delivery over Rs. 2,000</span><span>Stamped fresh every morning</span><span>Same-day dispatch in-city</span><span>Farm-direct sourcing, no middlemen</span>
  </div></div>
  <div class="wrap nav">
    <a href="{{ route('home') }}" class="logo"><span class="mark"><i data-lucide="leaf"></i></span>FreshCrate</a>
    <nav class="nav-links">
      <a href="{{ route('home') }}" class="active">Home</a>
      <a href="{{ route('shop') }}">Shop</a>
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('contact') }}">Contact</a>
    </nav>
    <div class="nav-actions">
      <a href="{{ route('account') }}" class="icon-btn" aria-label="Account"><i data-lucide="user"></i></a>
      <a href="{{ route('cart') }}" class="icon-btn" aria-label="Cart"><i data-lucide="shopping-basket"></i><span class="cart-count"></span></a>
      <button class="nav-toggle icon-btn" aria-label="Menu" aria-expanded="false"><i data-lucide="menu"></i></button>
    </div>
  </div>
</header>



<!-- HERO -->
<section class="hero">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <span class="eyebrow">Farm to your door, daily</span>
      <h1>Stamped fresh.<br>Delivered today.</h1>
      <p class="lede">FreshCrate sources straight from local growers and dairies, hand-checks every crate, and gets it to your kitchen the same day it's picked, milked or baked.</p>
      <div class="stamp-row">
        <div class="stamp sm">Same<br>Day</div>
        <div class="stamp sm citrus">No<br>Middle-<br>men</div>
        <div class="stamp sm tomato">Stock<br>Alerts</div>
      </div>
      <div class="btn-row">
        <a href="{{ route('shop') }}" class="btn btn-primary"><i data-lucide="shopping-basket"></i> Shop fresh produce</a>
        <a href="#how-it-works" class="btn btn-outline">How it works</a>
      </div>
      <div class="stat-row">
        <div class="stat"><b>{{ $productCount }}</b><span>Products curated</span></div>
        <div class="stat"><b>{{ $categories->count() }}</b><span>Category crates</span></div>
        <div class="stat"><b>{{ $supplierCount }}</b><span>Local suppliers</span></div>
      </div>
    </div>
    <div class="hero-art">
      <div class="crate">
        <svg viewBox="0 0 120 120"><path d="M60 40c-2-10-14-14-22-8" stroke="var(--pine)" stroke-width="5" fill="none" stroke-linecap="round"/><path d="M60 42c22 0 36 16 36 38s-16 36-36 36-36-14-36-36 14-38 36-38z" fill="var(--paper)"/><ellipse cx="44" cy="26" rx="10" ry="6" fill="var(--pine)" transform="rotate(-25 44 26)"/></svg>
      </div>
    </div>
  </div>
</section>

<!-- CATEGORIES -->
<section>
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">What's in season</span>
      <h2>Shop by crate</h2>
    </div>
    <div class="cat-grid">
      @foreach($categories as $category)
        <a href="{{ route('shop') }}" class="cat-card">
          <span class="icon-circle"><i data-lucide="tag"></i></span>
          <h3>{{ $category->name }}</h3>
          <span>{{ \App\Models\Product::where('category_id', $category->id)->where('status', 1)->count() }} items</span>
        </a>
      @endforeach
    </div>
  </div>
</section>

<!-- FEATURED PRODUCTS -->
<section>
  <div class="wrap">
    <div class="section-head row">
      <div>
        <span class="eyebrow">Stamped this week</span>
        <h2>Today's picks</h2>
      </div>
      <a href="{{ route('shop') }}" class="btn btn-outline">View full shop <i data-lucide="arrow-right"></i></a>
    </div>
    <div class="prod-grid">

      @forelse($featured as $product)
        @php
          $isOutOfStock = $product->quantity <= 0;
          $isLowStock = !$isOutOfStock && $product->quantity <= $product->min_stock;
        @endphp
        <a href="{{ route('product', $product->slug) }}" class="card prod-card">
          <div class="prod-media" style="width:100%; aspect-ratio: 4 / 3; overflow:hidden; position:relative;">
            @if($isOutOfStock)
              <div class="prod-badge"><span class="stamp sm tomato">Out of<br>Stock</span></div>
            @elseif($isLowStock)
              <div class="prod-badge"><span class="stamp sm tomato">Low<br>Stock</span></div>
            @else
              <div class="prod-badge"><span class="stamp sm">Fresh</span></div>
            @endif

            @if($product->image)
              <img src="{{ asset('public/storage/products/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:cover;">
            @else
              <svg viewBox="0 0 120 120"><rect x="30" y="30" width="60" height="60" rx="10" fill="var(--paper)" stroke="var(--pine)" stroke-width="4"/><circle cx="60" cy="60" r="18" fill="var(--citrus)"/></svg>
            @endif
          </div>
          <div class="prod-body">
            <span class="prod-cat">{{ $product->category->name ?? 'Uncategorised' }}</span>
            <h3 class="prod-name">{{ $product->name }}</h3>
            <div class="prod-foot">
              <span class="price">Rs. {{ number_format($product->selling_price, 0) }}</span>
              <button class="add-btn" data-add-to-cart data-name="{{ $product->name }}" aria-label="Add {{ $product->name }} to cart" onclick="event.preventDefault()" @if($isOutOfStock) disabled @endif><i data-lucide="plus"></i></button>
            </div>
          </div>
        </a>
      @empty
        <p>No products available right now — check back soon.</p>
      @endforelse

    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section id="how-it-works">
  <div class="wrap split">
    <div>
      <span class="eyebrow">From crate to counter</span>
      <h2>How your order moves</h2>
      <p class="lede">Every order follows the same four stops — the same path our admin team tracks on the back end, from stock to your step.</p>
    </div>
    <div class="steps">
      <div class="step"><span class="num">01</span><div><h3>Pick your crate</h3><p>Browse produce, dairy or pantry crates and add what you need.</p></div></div>
      <div class="step"><span class="num">02</span><div><h3>We hand-stamp for freshness</h3><p>Every item is checked against supplier stock before packing.</p></div></div>
      <div class="step"><span class="num">03</span><div><h3>Same-day dispatch</h3><p>Orders placed before 3pm leave the depot the same afternoon.</p></div></div>
      <div class="step"><span class="num">04</span><div><h3>Track to your door</h3><p>Follow your order status from your account, start to finish.</p></div></div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section>
  <div class="wrap">
    <div class="section-head text-center" style="align-items:center;">
      <span class="eyebrow">Word from the neighbourhood</span>
      <h2>Trusted by regulars</h2>
    </div>
    <div class="testi-row">
      <div class="card testi">
        <div class="stars"><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i></div>
        <p>"The produce actually tastes like it was picked yesterday — because it was. Delivery is quicker than I expected too."</p>
        <div class="who">Ayesha R. — Sargodha</div>
      </div>
      <div class="card testi">
        <div class="stars"><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i></div>
        <p>"Love that I can see exactly what's low on stock before I order. No more surprise substitutions."</p>
        <div class="who">Bilal K. — Lahore</div>
      </div>
      <div class="card testi">
        <div class="stars"><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i></div>
        <p>"Cash on delivery, clean packaging, and the eggs never arrive cracked. Been ordering weekly for months."</p>
        <div class="who">Sana M. — Faisalabad</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA BAND -->
<section>
  <div class="wrap">
    <div class="cta-band">
      <div class="cta-band-inner">
        <div>
          <h2>Get the weekly crate list</h2>
          <p>Fresh arrivals and low-stock alerts, straight to your inbox — no spam, just produce.</p>
        </div>
        <form class="field-row" style="grid-template-columns:1fr auto; gap:12px; min-width:340px;" data-demo-submit="You're on the list — welcome to FreshCrate.">
          <input type="email" placeholder="you@example.com" required aria-label="Email address" style="background:var(--paper);">
          <button class="btn btn-primary" type="submit">Subscribe</button>
        </form>
      </div>
    </div>
  </div>
</section>

<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <div class="logo" style="margin-bottom:14px;"><span class="mark"><i data-lucide="leaf"></i></span>FreshCrate</div>
        <p>Farm-fresh groceries, stamped and dispatched daily from our crates to your kitchen.</p>
        <div class="social-row">
          <a href="#" aria-label="Facebook"><i data-lucide="facebook"></i></a>
          <a href="#" aria-label="Instagram"><i data-lucide="instagram"></i></a>
          <a href="#" aria-label="Twitter"><i data-lucide="twitter"></i></a>
        </div>
      </div>
      <div>
        <h4>Shop</h4>
        <ul>
          <li><a href="{{ route('shop') }}#produce">Fresh Produce</a></li>
          <li><a href="{{ route('shop') }}#dairy">Dairy &amp; Eggs</a></li>
          <li><a href="{{ route('shop') }}#bakery">Bakery &amp; Pantry</a></li>
          <li><a href="{{ route('cart') }}">Your Cart</a></li>
        </ul>
      </div>
      <div>
        <h4>Company</h4>
        <ul>
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
          <li><a href="{{ route('account') }}">Account</a></li>
        </ul>
      </div>
      <div>
        <h4>Stay in the loop</h4>
        <p>Weekly picks &amp; low-stock alerts, no spam.</p>
        <form data-demo-submit="Subscribed!" style="display:flex; gap:8px;">
          <input type="email" placeholder="Email address" required aria-label="Email" style="background:rgba(251,247,236,0.08); border-color:rgba(251,247,236,0.25); color:var(--paper);">
          <button class="btn btn-primary" type="submit" style="padding:12px 18px;"><i data-lucide="arrow-right"></i></button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 FreshCrate Grocers · Built on the Food Website platform</span>
      <span>Crated with care in Punjab</span>
    </div>
  </div>
</footer>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('public/js/main.js') }}"></script>
</body>
</html>
