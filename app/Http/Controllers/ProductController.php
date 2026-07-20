<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllMainProducts();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = $this->productService->getActiveCategories();
        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        // checkbox ki value validated() mai nahi aati agar unchecked ho,
        // isliye alag se check karke array mai daal rahe hain
        $data['status'] = $request->has('status');
        $data['has_variants'] = $request->has('has_variants');

        $this->productService->createProduct($data, $request->file('image'));

        return redirect()->route('products.index')
            ->with('success', 'Product and Variants Added Successfully.');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $categories = $this->productService->getActiveCategories();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['status'] = $request->has('status');

        $this->productService->updateProduct($product, $data, $request->file('image'));

        return redirect()->route('products.index')
            ->with('success', 'Product Updated Successfully.');
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('products.index')
            ->with('success', 'Product Deleted Successfully.');
    }
}
