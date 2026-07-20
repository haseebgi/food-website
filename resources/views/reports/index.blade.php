@extends('layouts.admin')

@section('title', 'Sales Reports & Analytics')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4 mb-3">Reports & Analytics</h2>

    <!-- 💡 Filters Card Section -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-filter"></i> Filter Reports</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Order Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ $status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ $status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 💡 KPI Cards Section -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4 shadow-sm">
                <div class="card-body">
                    <h6>Total Revenue</h6>
                    <h3>Rs. {{ number_format($totalSales, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4 shadow-sm">
                <div class="card-body">
                    <h6>Total Orders</h6>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4 shadow-sm">
                <div class="card-body">
                    <h6>Collected Payments</h6>
                    <h3>Rs. {{ number_format($paidOrders, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark mb-4 shadow-sm">
                <div class="card-body">
                    <h6>Pending Receivables</h6>
                    <h3>Rs. {{ number_format($pendingOrders, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- 💡 Graph Reporting Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-graph-up"></i> Sales Trend Graph
        </div>
        <div class="card-body">
            <div style="height: 300px; width: 100%;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 💡 Detailed Filtered Table Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-table"></i> Filtered Order Records
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->customer->name ?? 'Walk-in Customer' }}</td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'Pending' ? 'warning text-dark' : ($order->status == 'Completed' ? 'success' : 'danger') }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No records found for the selected criteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 💡 Inject ChartJS dynamically via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Controller se dynamic array arrays parse ho kar JS me convert ho rhi hain
        const labels = {!! json_encode($graphLabels) !!};
        const dataValues = {!! json_encode($graphTotals) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Sales (Rs.)',
                    data: dataValues,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#0d6efd',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'Rs. ' + value; }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection