@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Add Product</h2>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Product Information</h5>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                {{-- Category --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>

                    <select name="category_id" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Product Name --}}
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                </div>

                {{-- Image --}}
                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                </div>

                {{-- Cost Price --}}
                <div class="mb-3">
                    <label class="form-label">Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price') }}">
                </div>

                {{-- Selling Price --}}
                <div class="mb-3">
                    <label class="form-label">Selling Price</label>
                    <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price') }}">
                </div>

                {{-- Quantity --}}
                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}">
                </div>

                {{-- Minimum Stock --}}
                <div class="mb-3">
                    <label class="form-label">Minimum Stock</label>
                    <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 5) }}">
                </div>

                {{-- Status --}}
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="status" value="1" id="status" checked>
                    <label class="form-check-label" for="status">Active</label>
                </div>

                {{-- 💡 Professional Variants Switch & Dynamic Fields --}}
                <div class="card my-4 bg-light border">
                    <div class="card-body">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="has_variants">Does this product have sizes/variants?</label>
                        </div>

                        <div id="variants_section" style="display: {{ old('has_variants') ? 'block' : 'none' }};">
                            <hr>
                            <h6 class="mb-3 text-secondary">Add Product Sizes & Prices</h6>
                            <div id="variants_container">
                                <div class="row g-2 align-items-center mb-2 variant-row">
                                    <div class="col-md-5">
                                        <select name="variants[0][size]" class="form-control">
                                            <option value="">Select Size</option>
                                            <option value="small">Small</option>
                                            <option value="medium">Medium</option>
                                            <option value="large">Large</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" name="variants[0][price]" class="form-control" placeholder="Price for this size" step="0.01">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm w-100 remove-variant-btn">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-dark btn-sm mt-2" id="add_variant_btn">+ Add More Size</button>
                        </div>
                    </div>
                </div>

                <button class="btn btn-success px-4">
                    Save Product
                </button>

            </form>

        </div>

    </div>

</div>

{{-- 💡 Dynamic Rows Handler Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hasVariantsCheckbox = document.getElementById('has_variants');
    const variantsSection = document.getElementById('variants_section');
    const variantsContainer = document.getElementById('variants_container');
    const addVariantBtn = document.getElementById('add_variant_btn');
    let variantIndex = 1;

    // Section Toggle
    hasVariantsCheckbox.addEventListener('change', function () {
        variantsSection.style.display = this.checked ? 'block' : 'none';
    });

    // Add More Sizes Row
    addVariantBtn.addEventListener('click', function () {
        const rowHTML = `
            <div class="row g-2 align-items-center mb-2 variant-row">
                <div class="col-md-5">
                    <select name="variants[${variantIndex}][size]" class="form-control" required>
                        <option value="">Select Size</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Price for this size" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm w-100 remove-variant-btn">Remove</button>
                </div>
            </div>
        `;
        variantsContainer.insertAdjacentHTML('beforeend', rowHTML);
        variantIndex++;
    });

    // Remove Selected Row
    variantsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-variant-btn')) {
            e.target.closest('.variant-row').remove();
        }
    });
});
</script>

@endsection