<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout — FreshCrate</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
<style>
  /* Fallback safe styling for radio options */
  .pay-option-block {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 12px;
    cursor: pointer;
  }
  .pay-option-block input[type="radio"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
  }
</style>
</head>
<body data-cart-count="{{ count($cart) }}">

<header class="site-header">
  <div class="wrap nav">
    <a href="{{ route('home') }}" class="logo"><span class="mark"><i data-lucide="leaf"></i></span>FreshCrate</a>
    <div class="nav-actions" style="margin-left:auto;">
      <span style="font-family:var(--font-mono); font-size:0.78rem; color:var(--ink-soft); display:flex; align-items:center; gap:8px;"><i data-lucide="lock" style="width:15px;height:15px;"></i> Secure checkout</span>
    </div>
  </div>
</header>

<div class="wrap crumb"><a href="{{ route('home') }}">Home</a><span class="sep">/</span><a href="{{ route('cart') }}">Cart</a><span class="sep">/</span>Checkout</div>

<section class="page-head">
  <div class="wrap">
    <h1>Checkout</h1>
    <p class="lede">Confirm your delivery details and pick a payment method — cash or digital wallet, your choice.</p>
  </div>
</section>

<section style="padding-top:0;">

  {{-- ✅ GLOBAL ERROR MESSAGE (controller ke catch block se aata hai) --}}
  @if (session('error'))
    <div class="wrap" style="margin-bottom:20px;">
      <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; border: 1px solid #f5c6cb;">
        <strong>{{ session('error') }}</strong>
      </div>
    </div>
  @endif

  {{-- ✅ GLOBAL SUCCESS MESSAGE --}}
  @if (session('success'))
    <div class="wrap" style="margin-bottom:20px;">
      <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; border: 1px solid #c3e6cb;">
        <strong>{{ session('success') }}</strong>
      </div>
    </div>
  @endif

<form action="{{ route('checkout.store') }}" method="POST" class="wrap checkout-grid" id="checkoutForm">
  @csrf

  <!-- LEFT COLUMN: Input Forms -->
  <div>
    
    <!-- VALIDATION ERROR ALERT DISPLAY -->
    @if ($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #f5c6cb;">
            <strong style="display:block; margin-bottom:5px;">Oops! Please fix the following errors:</strong>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Contact Card -->
    <div class="card" style="padding:32px; margin-bottom:24px;">
      <h3 style="margin-bottom:20px;">Contact</h3>
      <div class="field-row">
        <div class="field">
          <label>Full name</label>
          <input type="text" name="name" value="{{ old('name') }}" placeholder="Ayesha Raza" required>
          @error('name')
            <span style="color:#c0392b; font-size:0.82rem;">{{ $message }}</span>
          @enderror
        </div>
        <div class="field">
          <label>Phone number</label>
          <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="03XX-XXXXXXX" required>
          @error('phone')
            <span style="color:#c0392b; font-size:0.82rem;">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="field">
        <label>Email (Optional)</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com">
        @error('email')
          <span style="color:#c0392b; font-size:0.82rem;">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <!-- Delivery Address Card -->
    <div class="card" style="padding:32px; margin-bottom:24px;">
      <h3 style="margin-bottom:20px;">Delivery address</h3>
      <div class="field">
        <label>Street address</label>
        <input type="text" name="address" value="{{ old('address') }}" placeholder="House 12, Street 4, Model Town" required>
        @error('address')
          <span style="color:#c0392b; font-size:0.82rem;">{{ $message }}</span>
        @enderror
      </div>
      <div class="field-row">
        <div class="field">
          <label>City</label>
          <input type="text" name="city" value="{{ old('city', 'Sargodha') }}" placeholder="Sargodha" required>
          @error('city')
            <span style="color:#c0392b; font-size:0.82rem;">{{ $message }}</span>
          @enderror
        </div>
        <div class="field">
          <label>Postal code</label>
          <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="40100">
          @error('postal_code')
            <span style="color:#c0392b; font-size:0.82rem;">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div class="field">
        <label>Delivery notes (optional)</label>
        <textarea name="notes" rows="3" placeholder="Gate code, landmark, preferred time...">{{ old('notes') }}</textarea>
      </div>
    </div>

    <!-- Payment Options Radio Inputs (Cleaned & Native) -->
    <div class="card" style="padding:32px;">
      <h3 style="margin-bottom:20px;">Payment method</h3>
      <div class="pay-options-container">
        
        <label class="pay-option-block">
          <input type="radio" name="payment_method" value="cod" checked>
          <div>
            <strong>Cash on delivery</strong>
            <div style="font-size:0.82rem; color:var(--ink-soft);">Pay when your crate arrives</div>
          </div>
        </label>

        <label class="pay-option-block">
          <input type="radio" name="payment_method" value="jazzcash">
          <div>
            <strong>JazzCash</strong>
            <div style="font-size:0.82rem; color:var(--ink-soft);">Pay via mobile wallet</div>
          </div>
        </label>

        <label class="pay-option-block">
          <input type="radio" name="payment_method" value="easypaisa">
          <div>
            <strong>EasyPaisa</strong>
            <div style="font-size:0.82rem; color:var(--ink-soft);">Pay via mobile wallet</div>
          </div>
        </label>

      </div>
    </div>
  </div>

  <!-- RIGHT COLUMN: Order Summary Side Panel -->
  <div>
    <div class="card" style="padding:32px; position:sticky; top:24px;">
      <h3 style="margin-bottom:20px;">Order Summary</h3>
      
      <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:20px; font-size:0.95rem;">
        <div style="display:flex; justify-content:between; align-items:center;">
          <span style="color:var(--ink-soft);">Subtotal</span>
          <strong style="margin-left:auto;">Rs. {{ number_format($subtotal ?? $totalAmount ?? 0, 2) }}</strong>
        </div>
        <div style="display:flex; justify-content:between; align-items:center;">
          <span style="color:var(--ink-soft);">Delivery</span>
          <strong style="margin-left:auto;">
            {{ isset($deliveryCharge) && $deliveryCharge > 0 ? 'Rs. ' . number_format($deliveryCharge, 2) : 'Free' }}
          </strong>
        </div>
        <hr style="border:none; border-top:1px solid #eee; margin:8px 0;">
        <div style="display:flex; justify-content:between; align-items:center; font-size:1.2rem;">
          <span>Total</span>
          <strong style="margin-left:auto; color:var(--pine);">Rs. {{ number_format($totalAmount ?? $grandTotal ?? 0, 2) }}</strong>
        </div>
      </div>

      <!-- Submit Action Button (Using simple input type submit to force HTML standard form submission) -->
      <input type="submit" value="Place Order →" class="btn" style="width:100%; padding:16px; background-color: var(--pine); color: white; border: none; font-weight: bold; border-radius: 6px; cursor: pointer; text-align: center;">
    </div>
  </div>

</form>
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
<script>
  // Only initialize icons, no fancy form blocks
  lucide.createIcons();
</script>
</body>
</html>