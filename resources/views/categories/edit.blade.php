@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Edit Category</h2>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Update Category</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Category Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $category->name) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>

                    @if($category->image)
                        <img src="{{ asset('storage/categories/'.$category->image) }}"
                             width="100"
                             class="mb-2 rounded">
                    @else
                        <p>No Image</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Change Image</label>

                    <input type="file"
                           name="image"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>

                    <textarea name="description"
                              rows="4"
                              class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="form-check mb-3">

                    <input type="checkbox"
                           class="form-check-input"
                           name="status"
                           value="1"
                           {{ $category->status ? 'checked' : '' }}>

                    <label class="form-check-label">
                        Active
                    </label>

                </div>

                <button type="submit" class="btn btn-primary">
                    Update Category
                </button>

            </form>

        </div>

    </div>

</div>

@endsection