@extends('layouts.admin')

@section('title', 'Add Purchase')

@section('content')

<div class="container-fluid px-4">

    <h2 class="mt-4 mb-4">New Purchase</h2>

    <form action="{{ route('purchases.store') }}" method="POST">

        @csrf

        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-md-7">

                <div class="card">

                    <div class="card-header">

                        <h5>Products</h5>

                    </div>

                    <div class="card-body">

                        <input
                            type="text"
                            id="searchProduct"
                            class="form-control mb-3"
                            placeholder="Search Product...">

                        <div class="table-responsive">

                            <table class="table table-bordered">

                                <thead class="table-dark">

                                    <tr>

                                        <th>Product</th>
                                        <th>Cost Price</th>
                                        <th>Stock</th>
                                        <th width="90">Add</th>

                                    </tr>

                                </thead>

                                <tbody id="productTable">

                                @foreach($products as $product)

                                <tr class="product-item-row">

                                    <td class="product-search-name">

                                        {{ $product->name }}

                                        <input
                                            type="hidden"
                                            class="product-id"
                                            value="{{ $product->id }}">

                                    </td>

                                    <td>

                                        {{ number_format($product->cost_price,2) }}

                                    </td>

                                    <td>

                                        {{ $product->quantity }}

                                    </td>

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm addProduct"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->cost_price }}">

                                            <i class="fas fa-plus"></i>

                                        </button>

                                    </td>

                                </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            <!-- RIGHT SIDE -->
            <div class="col-md-5">

                <div class="card">

                    <div class="card-header">

                        <h5>Purchase Cart</h5>

                    </div>

                    <div class="card-body">

                        <!-- Supplier -->
                        <div class="mb-3">

                            <label class="form-label">Supplier</label>

                            <select
                                name="supplier_id"
                                class="form-control"
                                required>

                                <option value="">Select Supplier</option>

                                @foreach($suppliers as $supplier)

                                    <option value="{{ $supplier->id }}">

                                        {{ $supplier->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- Invoice -->
                        <div class="mb-3">

                            <label class="form-label">Invoice No</label>

                            <input
                                type="text"
                                name="invoice_no"
                                class="form-control"
                                value="INV-{{ time() }}"
                                readonly>

                        </div>

                        <!-- Payment Status -->
                        <div class="mb-3">

                            <label class="form-label">Payment Status</label>

                            <select
                                name="payment_status"
                                class="form-control">

                                <option value="Pending">Pending</option>

                                <option value="Paid">Paid</option>

                                <option value="Partial">Partial</option>

                            </select>

                        </div>

                        <!-- Remarks -->
                        <div class="mb-3">

                            <label class="form-label">Remarks</label>

                            <textarea
                                name="remarks"
                                rows="3"
                                class="form-control"></textarea>

                        </div>

                        <hr>

                        <h5>Selected Products</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Product</th>
                                        <th width="80">Qty</th>
                                        <th>Cost</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseCart">
                                    <!-- Dynamic rows yahan add honge -->
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mt-3">

                            <h4>

                                Grand Total :

                                Rs.

                                <span id="grandTotal">0.00</span>

                            </h4>

                        </div>

                        <button
                            type="submit" 
                            class="btn btn-primary w-100 mt-3">

                            Save Purchase

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection

@section('scripts')

<script>
// 1. Plus button par click karne ka behtar treeqa (Event Delegation)
document.getElementById('productTable').addEventListener('click', function (e) {
    // Agar plus icon ya button par click ho
    let button = e.target.closest('.addProduct');
    
    if (button) {
        let id = button.dataset.id;
        let name = button.dataset.name;
        let price = parseFloat(button.dataset.price);

        // Agar product pehle se cart mein ho to dubara add na ho
        if (document.getElementById('row' + id)) {
            alert('Product already added to cart!');
            return;
        }

        let row = `
        <tr id="row${id}">
            <td>
                ${name}
                <input type="hidden" name="product_id[]" value="${id}">
            </td>
            <td>
                <input
                    type="number"
                    class="form-control qty"
                    name="quantity[]"
                    value="1"
                    min="1"
                    data-price="${price}"
                    style="padding: 4px 8px;">
            </td>
            <td>
                ${price.toFixed(2)}
                <input type="hidden" name="cost_price[]" value="${price}">
            </td>
            <td>
                <span class="subtotal">${price.toFixed(2)}</span>
            </td>
        </tr>
        `;

        document.getElementById('purchaseCart').insertAdjacentHTML('beforeend', row);
        calculate();
    }
});

// 2. Quantity input change par calculation
document.addEventListener('input', function(e){
    if(e.target.classList.contains('qty')){
        
        // Agar user quantity khali chore to default 1 ho jaye
        if (e.target.value === '' || parseFloat(e.target.value) < 1) {
            e.target.value = 1;
        }

        calculate();
    }
});

// 3. Calculation function
function calculate(){
    let total = 0;

    document.querySelectorAll('#purchaseCart tr').forEach(function(row){
        let qtyInput = row.querySelector('.qty');
        if (qtyInput) {
            let qty = parseFloat(qtyInput.value) || 0;
            let price = parseFloat(qtyInput.dataset.price) || 0;
            let subtotal = qty * price;

            row.querySelector('.subtotal').innerText = subtotal.toFixed(2);
            total += subtotal;
        }
    });

    document.getElementById('grandTotal').innerText = total.toFixed(2);
}

// 4. Live Product Search
document.getElementById('searchProduct').addEventListener('keyup', function() {
    let filterValue = this.value.toLowerCase();
    let rows = document.querySelectorAll('#productTable .product-item-row');

    rows.forEach(function(row) {
        let productName = row.querySelector('.product-search-name').innerText.toLowerCase();
        
        if (productName.includes(filterValue)) {
            row.style.display = ""; 
        } else {
            row.style.display = "none"; 
        }
    });
});
</script>

@endsection