@extends('layouts.admin')

@section('title', 'Receive Payment')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2><i class="fas fa-money-bill-wave me-2"></i>Receive Customer Payment</h2>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to Payments Log
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Order Selection Dropdown -->
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold fs-5">Select Invoice / Order</label>
                        <select name="order_id" id="orderSelect" class="form-control form-control-lg" required>
                            <option value="">-- Choose Order to Pay --</option>
                            @foreach($orders as $ord)
                                <option value="{{ $ord->id }}" 
                                        data-total="{{ $ord->total_amount }}" 
                                        data-paid="{{ $ord->paid_amount }}" 
                                        data-due="{{ $ord->due_amount }}"
                                        data-customer="{{ $ord->customer->name ?? 'Walk-in Customer' }}"
                                        {{ (isset($selectedOrder) && $selectedOrder->id == $ord->id) ? 'selected' : '' }}>
                                    Invoice #{{ $ord->invoice_no }} - {{ $ord->customer->name ?? 'Walk-in' }} (Remaining: Rs. {{ number_format($ord->due_amount, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Live Dynamic Info Section -->
                <div class="row" id="infoSection" style="display: none;">
                    <div class="col-md-4 mb-4">
                        <div class="card bg-light border-start border-primary border-3 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted mb-1">Customer Name</h6>
                                <h4 id="lblCustomer" class="fw-bold text-secondary">-</h4>
                                
                                <h6 class="text-muted mt-3 mb-1">Grand Total</h6>
                                <h4 id="lblTotal" class="fw-bold text-primary">Rs. 0.00</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card bg-light border-start border-success border-3 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted mb-1">Already Paid Amount</h6>
                                <h4 id="lblPaid" class="fw-bold text-success">Rs. 0.00</h4>
                                
                                <h6 class="text-muted mt-3 mb-1">Net Due Balance</h6>
                                <h4 id="lblDue" class="fw-bold text-danger">Rs. 0.00</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Inputs -->
                    <div class="col-md-4 mb-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Amount Received Now (Rs.)</label>
                            <input type="number" name="amount_paid" id="amountPaidInput" class="form-control form-control-lg fw-bold text-success" 
                                   value="{{ old('amount_paid') }}" step="0.01" min="1" placeholder="0.00" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="EasyPaisa/JazzCash">EasyPaisa/JazzCash</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Remarks / Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Optional notes...">
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="btn btn-success btn-lg w-100 shadow-sm mt-3">
                    <i class="fas fa-check-circle me-2"></i>Post Payment Transaction
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Inline script directly inside content to avoid section bugs -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    let orderSelect = document.getElementById('orderSelect');
    
    function updatePaymentUI() {
        let selectedOption = orderSelect.options[orderSelect.selectedIndex];
        
        if(!selectedOption || orderSelect.value === "") {
            document.getElementById('infoSection').style.display = "none";
            return;
        }
        
        let total = parseFloat(selectedOption.getAttribute('data-total')) || 0;
        let paid = parseFloat(selectedOption.getAttribute('data-paid')) || 0;
        let due = parseFloat(selectedOption.getAttribute('data-due')) || 0;
        let customer = selectedOption.getAttribute('data-customer') || 'Walk-in Customer';

        document.getElementById('lblCustomer').innerText = customer;
        document.getElementById('lblTotal').innerText = "Rs. " + total.toFixed(2);
        document.getElementById('lblPaid').innerText = "Rs. " + paid.toFixed(2);
        document.getElementById('lblDue').innerText = "Rs. " + due.toFixed(2);
        
        let amountInput = document.getElementById('amountPaidInput');
        let btnSubmit = document.getElementById('btnSubmit');

        if (due <= 0) {
            alert("This order is already fully Paid! Select an order that has a remaining balance.");
            amountInput.value = "";
            amountInput.disabled = true;
            btnSubmit.disabled = true;
        } else {
            amountInput.disabled = false;
            btnSubmit.disabled = false;
            amountInput.max = due;
            amountInput.placeholder = "Max " + due.toFixed(2);
        }

        document.getElementById('infoSection').style.display = "flex";
    }

    orderSelect.addEventListener('change', updatePaymentUI);
    if(orderSelect.value !== "") {
        updatePaymentUI();
    }
});
</script>
@endsection