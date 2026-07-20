<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductService
{
    protected ProductImageService $imageService;
    protected ProductVariantService $variantService;

    public function __construct(
        ProductImageService $imageService,
        ProductVariantService $variantService
    ) {
        $this->imageService = $imageService;
        $this->variantService = $variantService;
    }

    public function getAllMainProducts()
    {
        return Product::with('category')->whereNull('parent_id')->latest()->get();
    }

    public function getActiveCategories()
    {
        return Category::where('status', 1)->get();
    }

    public function createProduct(array $data, $imageFile = null): Product
    {
        $imageName = $imageFile ? $this->imageService->upload($imageFile) : null;

        $mainProduct = Product::create([
            'category_id'   => $data['category_id'],
            'name'          => $data['name'],
            'slug'          => Str::slug($data['name']),
            'image'         => $imageName,
            'description'   => $data['description'] ?? null,
            'cost_price'    => $data['cost_price'],
            'selling_price' => $data['selling_price'],
            'quantity'      => $data['quantity'],
            'min_stock'     => $data['min_stock'],
            'status'        => !empty($data['status']) ? 1 : 0,
            'parent_id'     => null,
        ]);

        if (!empty($data['has_variants']) && !empty($data['variants'])) {
            $this->variantService->createVariants($mainProduct, $data['variants']);
        }

        return $mainProduct;
    }

    public function updateProduct(Product $product, array $data, $imageFile = null): Product
    {
        $imageName = $product->image;

        if ($imageFile) {
            $imageName = $this->imageService->replace($product->image, $imageFile);
        }

        $product->update([
            'category_id'   => $data['category_id'],
            'name'          => $data['name'],
            'slug'          => Str::slug($data['name']),
            'image'         => $imageName,
            'description'   => $data['description'] ?? null,
            'cost_price'    => $data['cost_price'],
            'selling_price' => $data['selling_price'],
            'quantity'      => $data['quantity'],
            'min_stock'     => $data['min_stock'],
            'status'        => !empty($data['status']),
        ]);

        return $product;
    }

    public function deleteProduct(Product $product): void
    {
        $this->imageService->delete($product->image);
        $product->delete();
    }
}
