@extends('layouts.admin')

@section('title', 'Add User')

@section('content')

<div class="container-fluid px-4">

    <h2 class="mt-4 mb-4">Add New User</h2>

    <div class="card">

        <div class="card-body">

            {{-- ✅ Agar koi bhi validation error ho to top par sab dikha do --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">Name</label>

                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           required>

                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">Email</label>

                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           required>

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">Phone</label>

                    <input type="text"
                           name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}">

                    @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">Role</label>

                    <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>

                        <option value="">Select Role</option>

                        @foreach($roles as $role)

                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>

                                {{ $role->name }}

                            </option>

                        @endforeach

                    </select>

                    @error('role_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    @if($roles->isEmpty())
                        <small class="text-danger d-block mt-1">
                            ⚠️ Koi role maujood nahi hai. Pehle Roles table mein role add karein.
                        </small>
                    @endif

                </div>

                <div class="mb-3">

                    <label class="form-label">Password</label>

                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required>

                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">Confirm Password</label>

                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-control @error('status') is-invalid @enderror">

                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>

                    </select>

                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                <button class="btn btn-primary">

                    Save User

                </button>

                <a href="{{ route('users.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection