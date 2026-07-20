@extends('layouts.admin')

@section('title', 'Suppliers')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">

        <h2>Suppliers</h2>

        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Supplier
        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">Supplier List</h5>

        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>#</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th width="170">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($suppliers as $supplier)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $supplier->name }}</td>

                        <td>{{ $supplier->company_name }}</td>

                        <td>{{ $supplier->phone }}</td>

                        <td>{{ $supplier->email }}</td>

                        <td>

                            @if($supplier->status == 'active')

                                <span class="badge bg-success">Active</span>

                            @else

                                <span class="badge bg-danger">Inactive</span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('suppliers.edit',$supplier->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('suppliers.destroy',$supplier->id) }}"
                                  method="POST"
                                  style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this supplier?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            No Suppliers Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection