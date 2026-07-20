@extends('layouts.admin')

@section('title', 'Orders')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Orders</h2>

        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Order
        </a>
    </div>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Error Messages (delete/update fail hone par yahan dikhega) --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Order List</h5>
            <a href="{{ route('orders.downloadAllPdf') }}" class="btn btn-danger btn-sm">
                Export All Orders
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Order No</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Order Type</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th width="170">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($orders as $order)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer->name ?? 'Walk-in Customer' }}</td>
                        <td>Rs. {{ number_format($order->total_amount,2) }}</td>
                        <td>{{ $order->order_type }}</td>
                        <td>{{ $order->payment_method }}</td>
                        <td>
                            @if($order->payment_status == 'Paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($order->status == 'Completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($order->status == 'Preparing')
                                <span class="badge bg-info">Preparing</span>
                            @elseif($order->status == 'Cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>

                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('orders.edit', $order->id) }}">Edit</a></li>
                                    <li><a class="dropdown-item text-info" href="{{ route('orders.downloadSinglePdf', $order->id) }}">Download PDF</a></li>
                                    <li><a class="dropdown-item text-primary" href="{{ route('orders.riderQr', $order->id) }}" target="_blank">📱 Rider QR</a></li>
                                    <li>
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center">
                            No Orders Found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>

        </div>

    </div>

</div>

@endsection