@extends('layouts.admin')

@section('title', 'Payments History')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2><i class="fas fa-history me-2"></i>Payment Transactions History</h2>
        <a href="{{ route('orders.index') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Collect New Payment
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">All Received Payments Log</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="60" class="text-center">Sr. No</th>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Customer</th>
                            <th>Method</th>
                            <th>Amount Paid</th>
                            <th>Notes</th>
                            <th width="120" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $key => $payment)
                        <tr>
                            <td class="text-center fw-bold">{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}</td>
                            <td class="fw-bold text-secondary">#{{ $payment->order->invoice_no ?? 'N/A' }}</td>
                            <td>{{ $payment->order->customer->name ?? 'Walk-in Customer' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $payment->payment_method }}</span>
                            </td>
                            <td class="fw-bold text-success">Rs. {{ number_format($payment->amount_paid, 2) }}</td>
                            <td><small class="text-muted">{{ $payment->notes ?? '-' }}</small></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <!-- Edit Button -->
                                    <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm" title="Edit Entry">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment? The order remaining balance will be increased!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Entry">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i> No payment transactions recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection