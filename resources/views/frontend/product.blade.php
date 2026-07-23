<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $product->name }} — FreshCrate</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
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
      <a href="{{ route('shop') }}" class="active">Shop</a>
      <a href="{{ route('about') }}">About</a>
      <a href="{{ route('contact') }}">Contact</a>
    </nav>
    <div class="nav-actions">
      <a href="{{ route('account') }}" class="icon-btn" aria-label="Account"><i data-lucide="user"></i></a>
      <a href="{{ route('cart') }}" class="icon-btn" aria-label="Cart">
        <i data-lucide="shopping-basket"></i>
        <span class="cart-count js-cart-global-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
      </a>
      <button class="nav-toggle icon-btn" aria-label="Menu" aria-expanded="false"><i data-lucide="menu"></i></button>
    </div>
  </div>
</header>

<div class="wrap crumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><a href="{{ route('shop') }}">Shop</a><span class="sep">/</span>{{ $product->name }}</div>

@php
  $isOutOfStock = $product->quantity <= 0;
  $isLowStock = !$isOutOfStock && $product->quantity <= $product->min_stock;
@endphp

<section>
  <div class="wrap pd-grid">
    <div class="pd-media">
      @if($product->image)
        <img src="{{ asset('public/storage/products/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
      @else
        <svg viewBox="0 0 120 120"><rect x="30" y="30" width="60" height="60" rx="10" fill="var(--paper)" stroke="var(--pine)" stroke-width="4"/><circle cx="60" cy="60" r="18" fill="var(--citrus)"/></svg>
      @endif
    </div>
    <div class="pd-info">
      <span class="eyebrow">{{ $product->category->name ?? 'Uncategorised' }}</span>
      <h1>{{ $product->name }}</h1>
      <div class="pd-price-row">
        <span class="price">Rs. {{ number_format($product->selling_price, 0) }}</span>
        @if($isOutOfStock)
          <span class="stamp sm tomato">Out of<br>Stock</span>
        @elseif($isLowStock)
          <span class="stamp sm tomato">Low<br>Stock</span>
        @else
          <span class="stamp sm">Fresh</span>
        @endif
      </div>
      <p class="lede">{{ $product->description ?? 'Fresh and checked against live stock before every dispatch.' }}</p>

      <div class="qty-row">
        <div class="qty-box">
          <button type="button" class="js-single-qty-modifier" data-action="dec" aria-label="Decrease quantity">−</button>
          <input type="text" value="1" inputmode="numeric" id="js-product-view-qty" readonly aria-label="Quantity">
          <button type="button" class="js-single-qty-modifier" data-action="inc" aria-label="Increase quantity">+</button>
        </div>
        <button class="btn btn-primary js-add-single-product" 
                data-product-id="{{ $product->id }}" 
                data-name="{{ $product->name }}" 
                @if($isOutOfStock) disabled @endif>
          <i data-lucide="shopping-basket"></i> Add to cart
        </button>
      </div>

      <div class="pd-meta">
        <div><i data-lucide="truck"></i> Same-day dispatch</div>
        <div><i data-lucide="shield-check"></i> Quality checked</div>
        <div><i data-lucide="package"></i> {{ $product->quantity }} in stock</div>
      </div>

      <div class="pd-tabs" data-tabs="product">
        <button class="pd-tab active" data-tab="desc">Description</button>
        <button class="pd-tab" data-tab="supplier">Supplier</button>
      </div>
      <div data-panel-group="product">
        <div class="pd-panel active" data-panel="desc">
          <p>{{ $product->description ?? 'No description added yet for this product.' }}</p>
        </div>
        <div class="pd-panel" data-panel="supplier">
          <p>Supplied by {{ $product->supplier->name ?? 'a regional produce partner' }}, checked against our inventory system on arrival before being listed for sale.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Pairs well with</span>
      <h2>You may also like</h2>
    </div>
    <div class="prod-grid">
      @forelse($related as $item)
        @php
          $itemOut = $item->quantity <= 0;
          $itemLow = !$itemOut && $item->quantity <= $item->min_stock;
        @endphp
        <a href="{{ route('product', $item->slug) }}" class="card prod-card">
          <div class="prod-media">
            @if($itemOut)
              <div class="prod-badge"><span class="stamp sm tomato">Out of<br>Stock</span></div>
            @elseif($itemLow)
              <div class="prod-badge"><span class="stamp sm tomato">Low<br>Stock</span></div>
            @else
              <div class="prod-badge"><span class="stamp sm">Fresh</span></div>
            @endif
            @if($item->image)
              <img src="{{ asset('storage/products/' . $item->image) }}" alt="{{ $item->name }}" style="width:100%; height:100%; object-fit:cover;">
            @else
              <svg viewBox="0 0 120 120"><rect x="30" y="30" width="60" height="60" rx="10" fill="var(--paper)" stroke="var(--pine)" stroke-width="4"/><circle cx="60" cy="60" r="18" fill="var(--citrus)"/></svg>
            @endif
          </div>
          <div class="prod-body">
            <span class="prod-cat">{{ $item->category->name ?? '' }}</span>
            <h3 class="prod-name">{{ $item->name }}</h3>
            <div class="prod-foot">
              <span class="price">Rs. {{ number_format($item->selling_price, 0) }}</span>
              <button class="add-btn js-add-to-crate" data-product-id="{{ $item->id }}" data-name="{{ $item->name }}" onclick="event.preventDefault()"><i data-lucide="plus"></i></button>
            </div>
          </div>
        </a>
      @empty
        <p>No related products in this category yet.</p>
      @endforelse
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
          <a href="#" aria-label="Instagram"><i data-lucide="indigo-instagram"></i></a>
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('js-product-view-qty');

    // Quantity Modifier Actions Selector
    document.querySelectorAll('.js-single-qty-modifier').forEach(btn => {
      btn.addEventListener('click', function() {
        let currentVal = parseInt(qtyInput.value);
        const action = this.getAttribute('data-action');
        
        if (action === 'inc') {
          currentVal++;
        } else if (action === 'dec' && currentVal > 1) {
          currentVal--;
        }
        qtyInput.value = currentVal;
      });
    });

    // Single Main View Add Button operational endpoint trigger
    const addBtn = document.querySelector('.js-add-single-product');
    if (addBtn) {
      addBtn.addEventListener('click', function() {
        const id = this.getAttribute('data-product-id');
        const finalQty = parseInt(qtyInput.value);

        executeCartAdditionRequest(id, finalQty);
      });
    }

    // Related cards helper trigger binding mapper 
    document.querySelectorAll('.js-add-to-crate').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-product-id');
        executeCartAdditionRequest(id, 1);
      });
    });

    function executeCartAdditionRequest(productId, totalQuantity) {
      fetch("{{ route('cart.add') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId, quantity: totalQuantity })
      })
      .then(res => {
        if (!res.ok) throw new Error('Network fault');
        return res.json();
      })
      .then(data => {
        if(data.success) {
          // Basket icon count badge auto update
          document.querySelectorAll('.js-cart-global-count').forEach(badge => {
            badge.innerText = data.cart_count;
          });
          
          // Agar layout se Toastr library ready hai toh chalayein warna automatic custom popup open ho
          if (typeof toastr !== 'undefined') {
            toastr.success(data.success);
          } else {
            showCustomFreshCratePopup(data.success, 'success');
          }
        } else if(data.error) {
          if (typeof toastr !== 'undefined') {
            toastr.error(data.error);
          } else {
            showCustomFreshCratePopup(data.error, 'error');
          }
        }
      })
      .catch(err => {
        console.error("Cart action failure trace:", err);
        if (typeof toastr !== 'undefined') {
          toastr.error('Something went wrong. Please try again.');
        } else {
          showCustomFreshCratePopup('Something went wrong. Please try again.', 'error');
        }
      });
    }

    // Auto HTML Popup Engine Injection (Crash-safe styling)
    function showCustomFreshCratePopup(message, type) {
      let popup = document.createElement('div');
      popup.innerText = message;
      popup.style.position = 'fixed';
      popup.style.top = '25px';
      popup.style.right = '25px';
      popup.style.padding = '14px 24px';
      popup.style.backgroundColor = type === 'success' ? '#1c4233' : '#a82c2c'; // Dark Forest Pine Green vs Soft Red
      popup.style.color = '#ffffff';
      popup.style.borderRadius = '6px';
      popup.style.boxShadow = '0 6px 16px rgba(0,0,0,0.12)';
      popup.style.zIndex = '999999';
      popup.style.fontSize = '15px';
      popup.style.fontFamily = "'Inter', sans-serif";
      popup.style.fontWeight = '500';
      popup.style.transition = 'all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';

      document.body.appendChild(popup);

      // 3.5 seconds clear block trace animation
      setTimeout(() => {
        popup.style.opacity = '0';
        popup.style.transform = 'translateY(-10px)';
        setTimeout(() => popup.remove(), 400);
      }, 3500);
    }
  });
</script>

</body>
</html>