<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shop — FreshCrate</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/css/style.css') }}">

<!-- 💡 SweetAlert2 CSS For Premium Toast Notifications -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

<div class="wrap crumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span>Shop</div>

<section class="page-head">
  <div class="wrap">
    <span class="eyebrow">{{ $products->count() }} items · stocked today</span>
    <h1>The full shop</h1>
    <p class="lede">Every crate we carry, checked against live stock. Items marked "Low Stock" are running out fast — order today.</p>
  </div>
</section>

<section style="padding-top:0;">
  <div class="wrap">
    <div class="filter-bar">
      <button class="chip active" data-filter="all">All items</button>
      @foreach($categories as $category)
        <button class="chip" data-filter="cat-{{ $category->id }}">{{ $category->name }}</button>
      @endforeach
    </div>

    <div class="prod-grid">

      @forelse($products as $product)
        @php
          $isOutOfStock = $product->quantity <= 0;
          $isLowStock = !$isOutOfStock && $product->quantity <= $product->min_stock;
          // Naye parent_id structure ke mutabiq child products (sizes) check ho rahe hain
          $hasVariants = $product->variants && $product->variants->isNotEmpty();
        @endphp
        <a href="{{ route('product', $product->slug) }}" class="card prod-card" data-category="cat-{{ $product->category_id }}">
          <div class="prod-media" style="width:100%; aspect-ratio: 4 / 3; overflow:hidden; position:relative;">
            @if($isOutOfStock)
              <div class="prod-badge"><span class="stamp sm tomato">Out of<br>Stock</span></div>
            @elseif($isLowStock)
              <div class="prod-badge"><span class="stamp sm tomato">Low<br>Stock</span></div>
            @else
              <div class="prod-badge"><span class="stamp sm">Fresh</span></div>
            @endif

            @if($product->image)
              <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:cover;">
            @else
              <svg viewBox="0 0 120 120"><rect x="30" y="30" width="60" height="60" rx="10" fill="var(--paper)" stroke="var(--pine)" stroke-width="4"/><circle cx="60" cy="60" r="18" fill="var(--citrus)"/></svg>
            @endif
          </div>
          <div class="prod-body">
            <span class="prod-cat">{{ $product->category->name ?? 'Uncategorised' }}</span>
            <h3 class="prod-name">{{ $product->name }}</h3>
            <p style="font-size:0.86rem; margin:0; color: #666; min-height: 40px;">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
            
            <!-- 💡 Dynamic Size Dropdown Layout (Jaise professional websites par hota ha) -->
            @if($hasVariants)
              <div class="variant-select-wrapper" style="margin: 12px 0 6px 0;">
                <label style="font-size: 0.72rem; color: #777; font-weight: 600; display: block; margin-bottom: 4px; letter-spacing: 0.5px;">CHOOSE SIZE:</label>
                <select class="js-variant-selector" 
                        data-product-id="{{ $product->id }}" 
                        onclick="event.stopPropagation(); event.preventDefault();"
                        style="width: 100%; padding: 8px 10px; border: 1px solid rgba(30,57,50,0.2); border-radius: 6px; font-size: 0.85rem; background: #fff; color: var(--pine); font-family: inherit; font-weight: 600; cursor: pointer; outline: none;">
                  @foreach($product->variants as $variant)
                    <option value="{{ $variant->id }}" data-price="{{ $variant->selling_price }}">
                      {{ ucfirst($variant->size) }} — Rs. {{ number_format($variant->selling_price, 0) }}
                    </option>
                  @endforeach
                </select>
              </div>
            @else
              <!-- Spacer taake non-variant cards ka structure collapse na kare -->
              <div style="margin: 40px 0 6px 0;"></div>
            @endif

            <div class="prod-foot" style="margin-top: auto; padding-top: 10px;">
              <!-- Live Dynamic Price display container -->
              <span class="price" id="price-target-{{ $product->id }}" style="font-weight: 700;">
                Rs. @if($hasVariants)
                      {{ number_format($product->variants->first()->selling_price, 0) }}
                    @else
                      {{ number_format($product->selling_price, 0) }}
                    @endif
              </span>
              
              <!-- Cart Add Button holding reference to the variant selection state -->
              <button class="add-btn js-add-to-crate" 
                      data-product-id="{{ $product->id }}" 
                      data-variant-id="{{ $hasVariants ? $product->variants->first()->id : '' }}"
                      data-name="{{ $product->name }}" 
                      aria-label="Add {{ $product->name }} to cart" 
                      onclick="event.stopPropagation(); event.preventDefault();" 
                      @if($isOutOfStock) disabled @endif>
                <i data-lucide="plus"></i>
              </button>
            </div>
          </div>
        </a>
      @empty
        <p>No products available right now — check back soon.</p>
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

<!-- 💡 SweetAlert2 JS Library For Elegant Notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    
    // 💡 SweetAlert2 Custom Toast Configuration (Light Green Aesthetic Theme)
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    // Dropdown value changes tracker engine
    document.querySelectorAll('.js-variant-selector').forEach(select => {
      select.addEventListener('change', function(e) {
        const productId = this.getAttribute('data-product-id');
        const selectedOption = this.options[this.selectedIndex];
        const newPrice = selectedOption.getAttribute('data-price');
        const variantId = this.value;

        // Card price update implementation
        const priceLabel = document.getElementById(`price-target-${productId}`);
        if(priceLabel) {
          priceLabel.innerText = `Rs. ${parseInt(newPrice).toLocaleString()}`;
        }

        // Add to cart payload alignment synchronization 
        const associatedBtn = document.querySelector(`.js-add-to-crate[data-product-id="${productId}"]`);
        if(associatedBtn) {
          associatedBtn.setAttribute('data-variant-id', variantId);
        }
      });
    });

    // Grid System Ajax dynamic add to cart pipeline
    document.querySelectorAll('.js-add-to-crate').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); 
        
        const id = this.getAttribute('data-product-id');
        const variantId = this.getAttribute('data-variant-id');
        const productName = this.getAttribute('data-name'); 
        
        fetch("{{ route('cart.add') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ 
            product_id: id, 
            variant_id: variantId ? variantId : null,
            quantity: 1 
          })
        })
        .then(res => res.json())
        .then(data => {
          if(data.success) {
            document.querySelectorAll('.js-cart-global-count').forEach(badge => {
              badge.innerText = data.cart_count;
            });
            
            // 💡 Beautiful Modern Green Toast Notification
            Toast.fire({
              icon: 'success',
              title: 'Success',
              text: data.success || `${productName} added to crate successfully!`,
              background: '#e8f5e9', // Light green background (Matches screenshot)
              iconColor: '#2e7d32',  // Dark Forest Green checkmark
              color: '#1b5e20'       // Dark Green Text for readability
            });
          } else if(data.error) {
            // Error Alert in same theme
            Toast.fire({
              icon: 'error',
              title: 'Oops...',
              text: data.error,
              background: '#ffebee', // Soft red background
              iconColor: '#c62828',
              color: '#b71c1c'
            });
          }
        })
        .catch(err => {
          console.error("Operational fault on grid trigger execution:", err);
          Toast.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again.',
            background: '#ffebee',
            iconColor: '#c62828',
            color: '#b71c1c'
          });
        });
      });
    });
  });
</script>
</body>
</html>