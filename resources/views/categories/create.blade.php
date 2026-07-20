@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')

<h2 class="mb-4">Add Category</h2>

<div class="card">
    <div class="card-body">

        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Category Name</label>

                <input type="text" name="name" class="form-control">

            </div>

            <div class="mb-3">
                <label class="form-label">Category Image</label>

                <input type="file" name="image" class="form-control">

            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>

                <textarea name="description" rows="4" class="form-control"></textarea>

            </div>

            <div class="form-check mb-3">

                <input type="checkbox" class="form-check-input" name="status" value="1">

                <label class="form-check-label">
                    Active
                </label>

            </div>

            <button type="submit" class="btn btn-success">
                Save Category
            </button>

        </form>

    </div>
</div>

@endsection