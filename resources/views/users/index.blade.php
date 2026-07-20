@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">

        <h2>Users</h2>

        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add User
        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">User List</h5>

        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="170">Action</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>{{ $user->phone }}</td>

                        <td>{{ $user->role?->name }}</td>

                        <td>

                            @if($user->status == 'active')

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

                            <a href="{{ route('users.edit', $user->id) }}"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <form action="{{ route('users.destroy', $user->id) }}"
                                  method="POST"
                                  style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this user?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            No Users Found

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection