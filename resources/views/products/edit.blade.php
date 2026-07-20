@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Edit Product</h2>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Update Product</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- Category --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>

                    <select name="category_id" class="form-control">

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}"
                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- Product Name --}}
                <div class="mb-3">
                    <label class="form-label">Product Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $product->name) }}">
                </div>

                {{-- Current Image --}}
                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>

                    @if($product->image)
                        <img src="{{ asset('storage/products/'.$product->image) }}"
                             width="100"
                             class="rounded mb-2">
                    @else
                        <p>No Image</p>
                    @endif
                </div>

                {{-- Change Image --}}
                <div class="mb-3">
                    <label class="form-label">Change Image</label>

                    <input type="file"
                           name="image"
                           class="form-control">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label">Description</label>

                    <textarea name="description"
                              rows="4"
                              class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Cost Price --}}
                <div class="mb-3">
                    <label class="form-label">Cost Price</label>

                    <input type="number"
                           step="0.01"
                           name="cost_price"
                           class="form-control"
                           value="{{ old('cost_price', $product->cost_price) }}">
                </div>

                {{-- Selling Price --}}
                <div class="mb-3">
                    <label class="form-label">Selling Price</label>

                    <input type="number"
                           step="0.01"
                           name="selling_price"
                           class="form-control"
                           value="{{ old('selling_price', $product->selling_price) }}">
                </div>

                {{-- Quantity --}}
                <div class="mb-3">
                    <label class="form-label">Quantity</label>

                    <input type="number"
                           name="quantity"
                           class="form-control"
                           value="{{ old('quantity', $product->quantity) }}">
                </div>

                {{-- Minimum Stock --}}
                <div class="mb-3">
                    <label class="form-label">Minimum Stock</label>

                    <input type="number"
                           name="min_stock"
                           class="form-control"
                           value="{{ old('min_stock', $product->min_stock) }}">
                </div>

                {{-- Status --}}
                <div class="form-check mb-3">

                    <input type="checkbox"
                           class="form-check-input"
                           name="status"
                           value="1"
                           {{ $product->status ? 'checked' : '' }}>

                    <label class="form-check-label">
                        Active
                    </label>

                </div>

                <button type="submit" class="btn btn-primary">
                    Update Product
                </button>

            </form>

        </div>

    </div>

</div>

@endsection