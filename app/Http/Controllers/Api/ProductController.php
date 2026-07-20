<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StorefrontProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

// ✅ Yeh controller wahi StorefrontProductService use karta hai jo web
// StorefrontController use karta hai. Koi logic duplicate nahi hui,
// sirf response JSON format mein hai instead of Blade view.
class ProductController extends Controller
{
    protected $productService;

    public function __construct(StorefrontProductService $productService)
    {
        $this->productService = $productService;
    }

    // GET /api/home
    public function home()
    {
        $featured = $this->productService->getFeaturedProducts();
        $categories = $this->productService->getAllCategories();
        $stats = $this->productService->getHomeStats();

        return response()->json([
            'success'    => true,
            'featured'   => $this->addProductImageUrls($featured),
            'categories' => $this->addCategoryImageUrls($categories),
            'stats'      => $stats,
        ]);
    }

    // GET /api/products
    public function index()
    {
        $products = $this->productService->getShopProducts();
        $categories = $this->productService->getAllCategories();

        return response()->json([
            'success'    => true,
            'products'   => $this->addProductImageUrls($products),
            'categories' => $this->addCategoryImageUrls($categories),
        ]);
    }

    // GET /api/products/{slug}
    public function show($slug)
    {
        try {
            $product = $this->productService->getProductBySlug($slug);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Product not found',
            ], 404);
        }

        $related = $this->productService->getRelatedProducts($product);

        $productData = $product->toArray();
        $productData['image_url'] = $this->productImageUrl($product->image);
        $productData['variants'] = collect($product->variants)->map(function ($variant) {
            $variantData = is_array($variant) ? $variant : $variant->toArray();
            $variantData['image_url'] = $this->productImageUrl($variantData['image'] ?? null);
            return $variantData;
        });

        return response()->json([
            'success' => true,
            'product' => $productData,
            'related' => $this->addProductImageUrls($related),
        ]);
    }

    // GET /api/categories
    public function categories()
    {
        return response()->json([
            'success'    => true,
            'categories' => $this->addCategoryImageUrls($this->productService->getAllCategories()),
        ]);
    }

    // =====================================================================
    // 🆕 Helper methods — image filename ko full URL mein badalte hain
    // =====================================================================

    // Agar aap ke images ka path different hai (e.g. "storage/uploads/products")
    // to yahan sirf yeh ek line change karni hai:
    private function productImageUrl(?string $filename): ?string
    {
        if (!$filename) {
            return null;
        }
        return asset('storage/products/' . $filename);
    }

    private function categoryImageUrl(?string $filename): ?string
    {
        if (!$filename) {
            return null;
        }
        return asset('storage/categories/' . $filename);
    }

    private function addProductImageUrls(Collection|array $products)
    {
        return collect($products)->map(function ($product) {
            $data = is_array($product) ? $product : $product->toArray();
            $data['image_url'] = $this->productImageUrl($data['image'] ?? null);
            return $data;
        });
    }

    private function addCategoryImageUrls(Collection|array $categories)
    {
        return collect($categories)->map(function ($category) {
            $data = is_array($category) ? $category : $category->toArray();
            $data['image_url'] = $this->categoryImageUrl($data['image'] ?? null);
            return $data;
        });
    }
}
