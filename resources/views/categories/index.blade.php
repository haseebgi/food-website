@extends('layouts.admin')

@section('title', 'Categories')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Categories</h2>

        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Category List</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th width="170">Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>

                                @if($category->image)

                                    <img src="{{ asset('storage/categories/'.$category->image) }}"
                                         width="70"
                                         height="70"
                                         style="object-fit:cover; border-radius:8px;">

                                @else

                                    No Image

                                @endif

                            </td>

                            <td>{{ $category->name }}</td>

                            <td>{{ $category->description }}</td>

                            <td>

                                @if($category->status)

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

                                <a href="{{ route('categories.edit',$category->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                               <form action="{{ route('categories.destroy', $category->id) }}"
                                method="POST"
                                style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                    Delete
                                </button>

                            </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center">
                                No Categories Found
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection