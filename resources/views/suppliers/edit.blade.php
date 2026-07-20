@extends('layouts.admin')

@section('title', 'Edit Supplier')

@section('content')

<div class="container-fluid px-4">

    <h2 class="mt-4 mb-4">Edit Supplier</h2>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('suppliers.update',$supplier->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Supplier Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name',$supplier->name) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text"
                           name="company_name"
                           class="form-control"
                           value="{{ old('company_name',$supplier->company_name) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="{{ old('phone',$supplier->phone) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email',$supplier->email) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea
                        name="address"
                        rows="3"
                        class="form-control">{{ old('address',$supplier->address) }}</textarea>
                </div>

                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-control">

                        <option value="active"
                            {{ $supplier->status=='active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ $supplier->status=='inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>

                <button class="btn btn-primary">
                    Update Supplier
                </button>

                <a href="{{ route('suppliers.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>

    </div>

</div>

@endsection