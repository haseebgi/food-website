@extends('layouts.admin')

@section('title', 'Products')

@section('content')

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h2>Products</h2>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Product List</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th width="170">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($products as $product)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            @if($product->image)

                                <img src="{{ asset('storage/products/'.$product->image) }}"
                                     width="70"
                                     height="70"
                                     style="object-fit:cover; border-radius:8px;">

                            @else

                                <span class="text-muted">No Image</span>

                            @endif
                        </td>

                       <!-- 💡 Puraani line ko is se replace karein (Null-safe operator `??` ke sath) -->
                        <td>{{ $product->category->name ?? 'No Category' }}</td>

                        <td>{{ $product->name }}</td>

                        <td>Rs. {{ number_format($product->cost_price, 2) }}</td>

                        <td>Rs. {{ number_format($product->selling_price, 2) }}</td>

                        <td>

                            @if($product->quantity == 0)

                                <span class="badge bg-danger">
                                    Out of Stock (0)
                                </span>

                            @elseif($product->quantity <= $product->min_stock)

                                <span class="badge bg-warning text-dark">
                                    Low Stock ({{ $product->quantity }})
                                </span>

                            @else

                                <span class="badge bg-success">
                                    In Stock ({{ $product->quantity }})
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($product->status)

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

                            <a href="{{ route('products.edit', $product->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('products.destroy', $product->id) }}"
                                  method="POST"
                                  style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9" class="text-center">
                            No Products Found
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection