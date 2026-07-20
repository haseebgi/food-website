@extends('layouts.admin')

@section('title', 'Customers')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Customers</h2>

        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Customer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Customer List</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th width="170">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($customers as $customer)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $customer->name }}</td>

                        <td>{{ $customer->email ?? '-' }}</td>

                        <td>{{ $customer->phone }}</td>

                        <td>{{ $customer->address ?? '-' }}</td>

                        <td>

                            @if($customer->status)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td>

                           <a href="{{ route('customers.edit', $customer->id) }}"
                                class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                          <form action="{{ route('customers.destroy', $customer->id) }}"
                                method="POST"
                                style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this customer?')">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center">
                            No Customers Found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection