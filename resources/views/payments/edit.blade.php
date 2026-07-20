@extends('layouts.admin')

@section('title', 'Edit Payment')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2>Edit Payment Entry for Invoice: #{{ $order->invoice_no }}</h2>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Info Box -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark fw-bold">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important Info</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Invoice Total:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
                    <p class="mb-2"><strong>Current Order Paid:</strong> Rs. {{ number_format($order->paid_amount, 2) }}</p>
                    <p class="mb-2 text-danger"><strong>This Entry Amount:</strong> Rs. {{ number_format($payment->amount_paid, 2) }}</p>
                    <hr>
                    <p class="mb-0 text-muted"><small>Updating this entry will automatically recalculate the order's pending due amount and auto-adjust its payment status (Paid/Partial/Pending).</small></p>
                </div>
            </div>
        </div>

        <!-- Form Box -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Modify Transaction Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5">Amount Received (Rs.)</label>
                            <input type="number" name="amount_paid" class="form-control form-control-lg fw-bold text-warning" 
                                   value="{{ $payment->amount_paid }}" step="0.01" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" value="{{ $payment->payment_date }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Payment Method</label>
                                <select name="payment_method" class="form-control">
                                    <option value="Cash" {{ $payment->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Bank Transfer" {{ $payment->payment_method == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="EasyPaisa/JazzCash" {{ $payment->payment_method == 'EasyPaisa/JazzCash' ? 'selected' : '' }}>EasyPaisa/JazzCash</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Payment Notes / Remarks</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $payment->notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 shadow-sm text-dark fw-bold">
                            <i class="fas fa-save me-2"></i>Update & Recalculate Balance
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection