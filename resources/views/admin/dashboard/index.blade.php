@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Dashboard Header with Professional Date Range Picker -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Dashboard</h1>
        
        <form method="GET" action="{{ route('admin.dashboard') }}" id="dateForm">
            <div class="input-group">
                <input type="text" name="date_range" id="dateRange" class="form-control shadow-sm" 
                       value="{{ request('date_range') ?: 'Select Date Range' }}" 
                       style="width: 250px; border-radius: 20px 0 0 20px; cursor: pointer; text-align: center;">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" style="border-radius: 0 20px 20px 0;">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- 1. Stats Cards (All restored) -->
    <div class="row">
        <!-- Products -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-primary shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Products</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div></div><div class="col-auto"><i class="fas fa-box fa-2x text-gray-300"></i></div></div></div></div></div>
        <!-- Categories -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-success shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Categories</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCategories }}</div></div><div class="col-auto"><i class="fas fa-tags fa-2x text-gray-300"></i></div></div></div></div></div>
        <!-- Customers -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-info shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Customers</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div></div><div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div></div></div></div></div>
        <!-- Suppliers -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-warning shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Suppliers</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuppliers }}</div></div><div class="col-auto"><i class="fas fa-truck fa-2x text-gray-300"></i></div></div></div></div></div>
        <!-- Users -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-danger shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Users</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div></div><div class="col-auto"><i class="fas fa-user-shield fa-2x text-gray-300"></i></div></div></div></div></div>
        <!-- Orders -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-dark shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Orders</div><div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div></div><div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-gray-300"></i></div></div></div></div></div>
        <!-- Total Sales -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-success shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Sales</div><div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($totalSales, 2) }}</div></div><div class="col-auto"><i class="fas fa-money-bill-wave fa-2x text-gray-300"></i></div></div></div></div></div>
        <!-- Total Expenses -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-danger shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Expenses</div><div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($totalExpenses, 2) }}</div></div><div class="col-auto"><i class="fas fa-wallet fa-2x text-gray-300"></i></div></div></div></div></div>
        <!-- Total Purchases -->
        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-warning shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Purchases</div><div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($totalPurchases, 2) }}</div></div><div class="col-auto"><i class="fas fa-shopping-basket fa-2x text-gray-300"></i></div></div></div></div></div>
    </div>

    <!-- Graphs Section -->
    <div class="row">
        <div class="col-xl-7 col-lg-6"><div class="card shadow mb-4"><div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Financial Overview</h6></div><div class="card-body" style="height: 300px;"><canvas id="financialChart"></canvas></div></div></div>
        <div class="col-xl-5 col-lg-6"><div class="card shadow mb-4"><div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Inventory Balance</h6></div><div class="card-body" style="height: 300px;"><canvas id="inventoryChart"></canvas></div></div></div>
    </div>

    <!-- Low Stock Table -->
    <div class="row"><div class="col-lg-12"><div class="card shadow mb-4"><div class="card-header py-3"><h6 class="m-0 font-weight-bold text-danger">Low Stock Products</h6></div><div class="card-body">@if($lowStockProducts->count())<table class="table table-bordered"><thead><tr><th>#</th><th>Product</th></tr></thead><tbody>@foreach($lowStockProducts as $product)<tr><td>{{ $loop->iteration }}</td><td>{{ $product->name }}</td></tr>@endforeach</tbody></table>@else<div class="alert alert-success mb-0">All products have sufficient stock.</div>@endif</div></div></div></div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
    $('#dateRange').daterangepicker({ locale: { format: 'YYYY-MM-DD' }, autoUpdateInput: false });
    $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        $('#dateForm').submit();
    });

    new Chart(document.getElementById('financialChart'), {
        type: 'bar',
        data: { labels: ['Sales', 'Expenses', 'Purchases'], datasets: [{ label: 'Amount', data: [{{ $totalSales }}, {{ $totalExpenses }}, {{ $totalPurchases }}], backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e'] }] },
        options: { maintainAspectRatio: false }
    });

    new Chart(document.getElementById('inventoryChart'), {
        type: 'doughnut',
        data: { labels: ['Products', 'Categories', 'Suppliers'], datasets: [{ data: [{{ $totalProducts }}, {{ $totalCategories }}, {{ $totalSuppliers }}], backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e'] }] },
        options: { maintainAspectRatio: false }
    });
</script>
@endsection