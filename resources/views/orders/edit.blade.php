@extends('layouts.admin')

@section('title', 'Edit Order')

@section('content')

<div class="container-fluid px-4">
    <h2 class="mt-4 mb-4">Edit Order #{{ $order->order_number }}</h2>

    {{-- ✅ Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div id="orderEditContainer">
        <div class="row">
            
            <!-- LEFT COLUMN: Products & Cart (Width 8) -->
            <div class="col-md-8">
                
                <!-- Products Selection Box -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Products Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" id="searchProduct" class="form-control" placeholder="🔍 Search Product by Name...">
                        </div>

                        <div id="productList" style="max-height: 250px; overflow-y: auto;">
                            <div class="row g-2">
                                @foreach($products as $product)
                                    <div class="col-md-6 product-card">
                                        <div class="card h-100 border">
                                            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 product-name">{{ $product->name }}</h6>
                                                    <small class="text-success fw-bold">Rs. {{ number_format($product->selling_price, 2) }}</small>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-sm" disabled>+ Add</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shopping Cart Display -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">🛒 Selected Items (Cart View)</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted my-2">Editing cart items directly via relation is restricted. Use order status tracking panel to manage progression.</p>
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Details</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="fw-bold text-dark">Current Order Cart Subtotal</span></td>
                                    <td class="fw-bold text-success">Rs. {{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <!-- Left Column End -->

            <!-- RIGHT COLUMN: Control Panel Side-by-Side (Width 4) -->
            <div class="col-md-4">
                
                <div class="card shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Control Panel</h5>
                    </div>
                    <div class="card-body">
                        
                        <!-- Customer Dropdown -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer</label>
                            <select id="field_customer_id" class="form-select form-control">
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Order Status -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">⚙️ Order Status (Live Tracking Timeline)</label>
                            <select id="field_order_live_status" name="status" class="form-control" style="background-color: #ffc107; color: #000; font-weight: bold; border: 1px solid #000;">
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Preparing" {{ $order->status == 'Preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Order Type -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Order Type</label>
                            <select id="field_order_type" class="form-select form-control">
                                <option value="Delivery" {{ $order->order_type == 'Delivery' ? 'selected' : '' }}>Delivery</option>
                                <option value="Dine In" {{ $order->order_type == 'Dine In' ? 'selected' : '' }}>Dine In</option>
                                <option value="Take Away" {{ $order->order_type == 'Take Away' ? 'selected' : '' }}>Take Away</option>
                            </select>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select id="field_payment_method" class="form-select form-control">
                                <option value="Cash" {{ $order->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Card" {{ $order->payment_method == 'Card' ? 'selected' : '' }}>Card</option>
                                <option value="JazzCash" {{ $order->payment_method == 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
                                <option value="EasyPaisa" {{ $order->payment_method == 'EasyPaisa' ? 'selected' : '' }}>EasyPaisa</option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Status</label>
                            <select id="field_payment_status" class="form-select form-control">
                                <option value="Pending" {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Order Notes</label>
                            <textarea id="field_notes" rows="2" class="form-control">{{ $order->notes }}</textarea>
                        </div>

                        <hr>

                        <!-- Grand Total Display -->
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
                            <span class="fw-bold h5 mb-0">Grand Total:</span>
                            <span class="text-success fw-bold h4 mb-0">Rs. {{ number_format($order->total_amount, 2) }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg fw-bold" type="button" id="ajaxSubmitBtn">
                                💾 Update Order Status
                            </button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm text-center">
                                Cancel & Back
                            </a>
                        </div>

                    </div>
                </div>

            </div> <!-- Right Column End -->
            
        </div> <!-- Row End -->
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Product Search Logic
    const searchInput = document.getElementById('searchProduct');
    const productCards = document.querySelectorAll('.product-card');

    if(searchInput) {
        searchInput.addEventListener('input', function() {
            let filter = this.value.toLowerCase().trim();
            productCards.forEach(function(card) {
                let productName = card.querySelector('.product-name').innerText.toLowerCase();
                if (productName.includes(filter)) {
                    card.style.setProperty('display', 'block', 'important');
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            });
        });
    }

    // 2. Verified Absolute Form Submission Engine
    const submitBtn = document.getElementById('ajaxSubmitBtn');
    if(submitBtn) {
        submitBtn.addEventListener('click', function() {
            submitBtn.innerText = "Saving Changes...";
            submitBtn.disabled = true;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('orders.update', $order->id) }}";

            // CSRF Token Protection
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // PUT Request Method For Resource Route
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            // Mapped payload data
            const fields = {
                'status': document.getElementById('field_order_live_status').value, 
                'order_type': document.getElementById('field_order_type').value,
                'payment_method': document.getElementById('field_payment_method').value,
                'payment_status': document.getElementById('field_payment_status').value,
                'notes': document.getElementById('field_notes').value
            };

            for (const [key, value] of Object.entries(fields)) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = key;
                hiddenInput.value = value;
                form.appendChild(hiddenInput);
            }

            document.body.appendChild(form);
            form.submit();
        });
    }
});
</script>
@endsection