@extends('layouts.admin')

@section('title', 'Purchases List')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2>Purchases</h2>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Add New Purchase
        </a>
    </div>

    <!-- Alert Messages (Success/Error) -->
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
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Purchase History</h5>
            <!-- Search Box for Local Filter -->
            <input type="text" id="searchPurchase" class="form-control w-25" placeholder="Search Invoice or Supplier...">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="60" class="text-center">Sr. No</th>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Supplier</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Remarks</th>
                            <th width="120" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="purchaseTableBody">
                        @forelse($purchases as $key => $purchase)
                        <tr class="purchase-row">
                            <td class="text-center fw-bold">{{ $key + 1 }}</td>
                            <td>{{ $purchase->created_at->format('d-M-Y h:i A') }}</td>
                            <td class="fw-bold text-secondary">{{ $purchase->invoice_no }}</td>
                            <td class="supplier-name">{{ $purchase->supplier->name ?? 'N/A' }}</td>
                            <td class="fw-bold text-primary">Rs. {{ number_format($purchase->total_amount, 2) }}</td>
                            <td>
                                @if($purchase->payment_status == 'Paid')
                                    <span class="badge bg-success p-2">Paid</span>
                                @elseif($purchase->payment_status == 'Partial')
                                    <span class="badge bg-warning text-dark p-2">Partial</span>
                                @else
                                    <span class="badge bg-danger p-2">Pending</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $purchase->remarks ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <!-- View Details Button -->
                                    <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-info btn-sm text-white" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Delete/Cancel Form with Safe Revert Warning -->
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this purchase? This will revert the product stock!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete/Cancel Purchase">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i> No purchase records found.
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

@section('scripts')
<script>
// Live Search Filter for Purchases Page
document.getElementById('searchPurchase').addEventListener('keyup', function() {
    let filterValue = this.value.toLowerCase();
    let rows = document.querySelectorAll('#purchaseTableBody .purchase-row');

    rows.forEach(function(row) {
        let invoice = row.cells[2].innerText.toLowerCase();
        let supplier = row.querySelector('.supplier-name').innerText.toLowerCase();
        
        if (invoice.includes(filterValue) || supplier.includes(filterValue)) {
            row.style.display = ""; 
        } else {
            row.style.display = "none"; 
        }
    });
});
</script>
@endsection