@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Edit Customer</h2>

        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Update Customer</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('customers.update', $customer->id) }}" method="POST">

                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">Customer Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $customer->name) }}">

                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $customer->email) }}">

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label class="form-label">Phone</label>

                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="{{ old('phone', $customer->phone) }}">

                    @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label class="form-label">Address</label>

                    <textarea name="address"
                              rows="3"
                              class="form-control">{{ old('address', $customer->address) }}</textarea>

                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="form-check mb-3">

                    <input class="form-check-input"
                           type="checkbox"
                           name="status"
                           value="1"
                           {{ $customer->status ? 'checked' : '' }}>

                    <label class="form-check-label">
                        Active
                    </label>

                </div>

                <button type="submit" class="btn btn-primary">
                    Update Customer
                </button>

            </form>

        </div>

    </div>

</div>

@endsection