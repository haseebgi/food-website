@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Add Expense Category</h2>
        <a href="{{ route('expense-categories.index') }}" class="btn btn-secondary">
            &larr; Back to List
        </a>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-body">
            <form action="{{ route('expense-categories.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Electricity Bill, Shop Rent" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Brief details about this category">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" checked>
                    <label class="form-check-label" for="is_active">Active (Available for use)</label>
                </div>

                <button type="submit" class="btn btn-success">Save Category</button>
            </form>
        </div>
    </div>
</div>
@endsection