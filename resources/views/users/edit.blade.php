@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<div class="container-fluid px-4">

    <h2 class="mt-4 mb-4">Edit User</h2>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('users.update', $user->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">Name</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $user->name) }}"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $user->email) }}"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">Phone</label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $user->phone) }}">

                </div>

                <div class="mb-3">

                    <label class="form-label">Role</label>

                    <select name="role_id" class="form-control" required>

                        @foreach($roles as $role)

                            <option
                                value="{{ $role->id }}"
                                {{ $user->role_id == $role->id ? 'selected' : '' }}>

                                {{ $role->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        New Password
                        <small class="text-muted">(Optional)</small>
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-control">

                        <option
                            value="active"
                            {{ $user->status == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ $user->status == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>

                <button class="btn btn-primary">
                    Update User
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