@extends('layouts.admin')

@section('title', 'Add Order')

@section('content')

<div class="container-fluid px-4">

    <h2 class="mt-4 mb-4">Add New Order</h2>

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

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        
        <div class="row">
            
            <!-- LEFT COLUMN: Products & Cart (Main Area) -->
            <div class="col-md-8">
                
                <!-- Products Selection -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Products Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" 
                                   id="searchProduct" 
                                   class="form-control" 
                                   placeholder="🔍 Search Product by Name...">
                        </div>

                        <div id="productList" style="max-height: 250px; overflow-y: auto;">
                            <div class="row g-2">
                                @foreach($products as $product)
                                    <div class="col-md-6 product-card">
                                        <div class="card h-100 border">
                                            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 product-name">{{ $product->name }}</h6>
                                                    <small class="text-success fw-bold">
                                                        Rs. {{ number_format($product->selling_price, 2) }}
                                                    </small>
                                                </div>
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm addProduct" 
                                                        data-id="{{ $product->id }}" 
                                                        data-name="{{ $product->name }}" 
                                                        data-price="{{ $product->selling_price }}">
                                                    + Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shopping Cart -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">🛒 Selected Items (Cart)</h5>
                    </div>
                    <div class="card-body">
                        <div style="max-height: 350px; overflow-y: auto;">
                            <table class="table align-middle" id="cartTable" style="display: none;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Name</th>
                                        <th style="width: 120px;">Quantity</th>
                                        <th>Total Price</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="cartItemsContainer">
                                    <!-- Dynamic items will load here -->
                                </tbody>
                            </table>
                        </div>

                        <div id="emptyCartMessage">
                            <p class="text-muted text-center my-4">No Product Selected Yet</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Order & Payment Details (Checkout Sidebar) -->
            <div class="col-md-4">
                
                <div class="card shadow-sm border-primary sticky-top" style="top: 20px; z-index: 10;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary & Payment</h5>
                    </div>
                    <div class="card-body">
                        
                        <!-- Customer -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Order Type -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Order Type</label>
                            <select name="order_type" class="form-control">
                                <option value="Dine In" selected>Dine In (Physical Shop)</option>
                                <option value="Take Away">Take Away (Physical Shop)</option>
                            </select>
                        </div>

                        <hr>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="JazzCash">JazzCash</option>
                                <option value="EasyPaisa">EasyPaisa</option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Status</label>
                            <select name="payment_status" class="form-control">
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Order Notes</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Special instructions..."></textarea>
                        </div>

                        <hr>

                        <!-- Grand Total Display -->
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
                            <span class="fw-bold h5 mb-0">Grand Total:</span>
                            <span class="text-success fw-bold h4 mb-0">Rs. <span id="grandTotal">0.00</span></span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg" type="submit">
                                🚀 Place Order
                            </button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm text-center">
                                Cancel & Back
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>
        <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
    </form>

</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const cartTable = document.getElementById('cartTable');
    const emptyCartMessage = document.getElementById('emptyCartMessage');
    const grandTotalSpan = document.getElementById('grandTotal');
    const totalAmountInput = document.getElementById('totalAmountInput');
    const searchInput = document.getElementById('searchProduct');
    const productCards = document.querySelectorAll('.product-card');

    // 1. LIVE PRODUCT SEARCH FUNCTIONALITY
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

    // 2. ADD PRODUCT TO CART
    document.querySelectorAll('.addProduct').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = parseFloat(this.dataset.price);

            let existingRow = document.querySelector(`tr[data-product-id="${id}"]`);

            if (existingRow) {
                let qtyInput = existingRow.querySelector('.cart-qty');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                updateRowTotal(existingRow);
            } else {
                const tr = document.createElement('tr');
                tr.setAttribute('data-product-id', id);
                tr.setAttribute('data-price', price);
                
                tr.innerHTML = `
                    <td>
                        <span class="d-block fw-bold">${name}</span>
                        <small class="text-muted">Rs. ${price.toFixed(2)}</small>
                        <input type="hidden" name="products[${id}][product_id]" value="${id}">
                    </td>
                    <td>
                        <input type="number" 
                               name="products[${id}][quantity]" 
                               value="1" 
                               min="1" 
                               class="form-control form-control-sm cart-qty">
                    </td>
                    <td class="fw-bold text-dark">Rs. <span class="row-subtotal">${price.toFixed(2)}</span></td>
                    <td>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-cart-item" style="padding: 2px 8px;">&times;</button>
                    </td>
                `;
                
                cartItemsContainer.appendChild(tr);

                tr.querySelector('.cart-qty').addEventListener('input', function() {
                    if(parseInt(this.value) < 1 || this.value === '') this.value = 1;
                    updateRowTotal(tr);
                });

                tr.querySelector('.remove-cart-item').addEventListener('click', function() {
                    tr.remove();
                    toggleCartVisibility();
                    calculateGrandTotal();
                });
            }

            toggleCartVisibility();
            calculateGrandTotal();
        });
    });

    function updateRowTotal(row) {
        const price = parseFloat(row.getAttribute('data-price'));
        const qty = parseInt(row.querySelector('.cart-qty').value) || 0;
        const subtotal = price * qty;
        row.querySelector('.row-subtotal').innerText = subtotal.toFixed(2);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('#cartItemsContainer tr').forEach(function(row) {
            const price = parseFloat(row.getAttribute('data-price'));
            const qty = parseInt(row.querySelector('.cart-qty').value) || 0;
            grandTotal += (price * qty);
        });
        
        grandTotalSpan.innerText = grandTotal.toFixed(2);
        
        if (totalAmountInput) {
            totalAmountInput.value = grandTotal.toFixed(2);
        }
    }

    function toggleCartVisibility() {
        if (cartItemsContainer.children.length > 0) {
            cartTable.style.display = 'table';
            emptyCartMessage.style.display = 'none';
        } else {
            cartTable.style.display = 'none';
            emptyCartMessage.style.display = 'block';
        }
    }

    // 3. Form Submit se pehle validate karo ke cart empty na ho
    const orderForm = document.querySelector('form[action="{{ route('orders.store') }}"]');
    orderForm.addEventListener('submit', function(e) {
        if (cartItemsContainer.children.length === 0) {
            e.preventDefault();
            alert('Please add at least one product to the cart before placing the order.');
        }
    });
});
</script>
@endsection