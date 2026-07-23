<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Cart — FreshCrate</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
</head>
<body data-cart-count="{{ count($cart) }}">

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
      <a href="{{ route('contact') }}">Contact</a>
    </nav>
    <div class="nav-actions">
      <a href="{{ route('account') }}" class="icon-btn" aria-label="Account"><i data-lucide="user"></i></a>
      <a href="{{ route('cart') }}" class="icon-btn" aria-label="Cart">
        <i data-lucide="shopping-basket"></i>
        <span class="cart-count js-cart-global-count">{{ count($cart) }}</span>
      </a>
      <button class="nav-toggle icon-btn" aria-label="Menu" aria-expanded="false"><i data-lucide="menu"></i></button>
    </div>
  </div>
</header>

<div class="wrap crumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span>Cart</div>

<section class="page-head">
  <div class="wrap">
    <h1>Your crate</h1>
    <p class="lede">Review your order before checkout. Quantities and totals update automatically.</p>
  </div>
</section>

<section style="padding-top:0;">
  <div class="wrap cart-layout">

    @if(count($cart) > 0)
    {{-- Cart Items Left Container --}}
    <div class="js-cart-items-container">
      @foreach($cart as $id => $details)
      <div class="cart-line" data-id="{{ $id }}">
        <div class="thumb">
          {{-- 💡 Changed path from storage/ to storage/products/ so uploaded items map correctly --}}
          @if(!empty($details['image']))
            <img src="{{ asset('public/storage/products/' . $details['image']) }}" alt="{{ $details['name'] }}" style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
          @else
            {{-- Default placeholder svg fallback --}}
            <svg viewBox="0 0 120 120"><path d="M60 42c22 0 36 16 36 38s-16 36-36 36-36-14-36-36 14-38 36-38z" fill="var(--tomato)"/></svg>
          @endif
        </div>
        <div>
          <div class="name">{{ $details['name'] }}</div>
          <div class="sub">{{ $details['category'] }} · Rs. {{ number_format($details['price'], 0) }}</div>
        </div>
        <div class="qty-box">
          <button type="button" class="js-qty-btn" data-action="dec" aria-label="Decrease quantity">−</button>
          <input type="text" value="{{ $details['quantity'] }}" inputmode="numeric" class="js-qty-input" readonly aria-label="Quantity">
          <button type="button" class="js-qty-btn" data-action="inc" aria-label="Increase quantity">+</button>
        </div>
        <span class="price js-item-subtotal">Rs. {{ number_format($details['price'] * $details['quantity'], 0) }}</span>
        <button class="icon-btn remove-btn js-remove-item" aria-label="Remove {{ $details['name'] }}"><i data-lucide="trash-2"></i></button>
      </div>
      @endforeach

      <a href="{{ route('shop') }}" class="btn btn-outline" style="margin-top:24px;"><i data-lucide="arrow-left"></i> Continue shopping</a>
    </div>

    {{-- Order Summary Sidebar Right Card --}}
    <div class="card summary-card js-summary-sidebar">
      <h3 style="margin-bottom:18px;">Order summary</h3>
      <div class="summary-row"><span>Subtotal</span><span class="js-subtotal-val">Rs. {{ number_format($subtotal, 0) }}</span></div>
      <div class="summary-row"><span>Delivery</span><span class="js-delivery-val">Rs. {{ number_format($deliveryCharge, 0) }}</span></div>
      <div class="summary-row"><span>Promo</span><span>—</span></div>
      <div class="summary-row total"><span>Total</span><span class="js-total-val">Rs. {{ number_format($totalAmount, 0) }}</span></div>

      <div class="field" style="margin-top:20px;">
        <label>Promo code</label>
        <div style="display:flex; gap:8px;">
          <input type="text" placeholder="Enter code">
          <button class="btn btn-outline" type="button">Apply</button>
        </div>
      </div>

      <a href="{{ route('checkout') }}" class="btn btn-primary btn-block" style="margin-top:12px;">Proceed to checkout <i data-lucide="arrow-right"></i></a>
      <p style="font-size:0.8rem; margin-top:14px; text-align:center;">Free delivery on orders over Rs. 2,000</p>
    </div>
    @else
    {{-- Clean Professional Empty Cart State layout --}}
    <div style="text-align: center; width: 100%; padding: 60px 20px;" class="js-empty-state-view">
      <div style="margin-bottom: 20px; color: var(--pine); opacity: 0.5;">
         <i data-lucide="shopping-basket" style="width: 80px; height: 80px;"></i>
      </div>
      <h2>Your crate is looking light!</h2>
      <p style="margin-bottom: 24px; color: #666;">You haven't added any fresh items to your crate yet.</p>
      <a href="{{ route('shop') }}" class="btn btn-primary">Explore Our Shop</a>
    </div>
    @endif

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

{{-- Async AJAX Pipeline Handling Architecture Execution --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 1. Quantity Adjustments Interaction
    document.querySelectorAll('.js-qty-btn').forEach(button => {
      button.addEventListener('click', function() {
        const lineItem = this.closest('.cart-line');
        const itemId = lineItem.getAttribute('data-id');
        const inputField = lineItem.querySelector('.js-qty-input');
        let currentQty = parseInt(inputField.value);
        const action = this.getAttribute('data-action');

        if(action === 'inc') {
          currentQty++;
        } else if(action === 'dec' && currentQty > 1) {
          currentQty--;
        } else {
          return; // Quantities cannot fall below 1
        }

        inputField.value = currentQty;
        updateCartBackend(itemId, currentQty, lineItem);
      });
    });

    // 2. Direct Item Deletion Pipeline
    document.querySelectorAll('.js-remove-item').forEach(button => {
      button.addEventListener('click', function() {
        const lineItem = this.closest('.cart-line');
        const itemId = lineItem.getAttribute('data-id');
        
        if(confirm('Are you sure you want to remove this item?')) {
          removeItemFromBackend(itemId, lineItem);
        }
      });
    });

    // Axios Async Session Operations Mapper Engine
    function updateCartBackend(id, quantity, element) {
      fetch("{{ route('cart.update') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id: id, quantity: quantity })
      })
      .then(res => res.json())
      .then(data => {
        if(data.success) {
          element.querySelector('.js-item-subtotal').innerText = data.item_subtotal;
          refreshInterfaceTotals(data);
        }
      })
      .catch(err => console.error("Session sync failed:", err));
    }

    function removeItemFromBackend(id, element) {
      fetch("{{ route('cart.remove') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id: id })
      })
      .then(res => res.json())
      .then(data => {
        if(data.success) {
          element.remove();
          refreshInterfaceTotals(data);
          
          // Sync Navigation Globals Count Badges
          document.querySelectorAll('.js-cart-global-count').forEach(badge => {
            badge.innerText = data.cart_count;
          });

          // Check if cart became completely empty
          if(data.cart_count === 0) {
            location.reload(); // Reload triggers structural layout transformation automatically
          }
        }
      })
      .catch(err => console.error("Deletion mapping fault:", err));
    }

    function refreshInterfaceTotals(payload) {
      if(document.querySelector('.js-subtotal-val')) {
        document.querySelector('.js-subtotal-val').innerText = payload.subtotal;
        document.querySelector('.js-delivery-val').innerText = payload.delivery;
        document.querySelector('.js-total-val').innerText = payload.total;
      }
    }
    
    // Lucide Icons initialization verification tracker
    if (typeof lucide !== 'undefined') {
       lucide.createIcons();
    }
  });
</script>
</body>
</html>