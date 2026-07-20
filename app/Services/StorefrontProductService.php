<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

// ✅ Sirf storefront pe product dikhane ka kaam (home, shop, single product page).
// Cart ya checkout se koi lena dena nahi.
class StorefrontProductService
{
    public function getFeaturedProducts(int $limit = 6)
    {
        return Product::with(['category', 'variants'])
            ->whereNull('parent_id')
            ->where('status', 1)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getShopProducts()
    {
        return Product::with(['category', 'variants'])
            ->whereNull('parent_id')
            ->where('status', 1)
            ->latest()
            ->get();
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function getHomeStats(): array
    {
        return [
            'productCount'  => Product::where('status', 1)->whereNull('parent_id')->count(),
            'supplierCount' => Supplier::count(),
        ];
    }

    public function getProductBySlug(string $slug): Product
    {
        return Product::with(['category', 'variants'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();
    }

    public function getRelatedProducts(Product $product, int $limit = 3)
    {
        return Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereNull('parent_id')
            ->where('status', 1)
            ->take($limit)
            ->get();
    }
}
