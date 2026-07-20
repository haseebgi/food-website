<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes (Mobile App / External Clients)
|--------------------------------------------------------------------------
| Yeh sab routes automatically "/api" prefix ke sath chalti hain.
| Yani neeche "/products" likha hai to actual URL hoga: /api/products
|
| Aap ke web.php mein koi change nahi kiya — yeh totally alag file hai.
*/

// Home page data (featured products, categories, stats)
Route::get('/home', [ProductController::class, 'home']);

// Shop - sab products
Route::get('/products', [ProductController::class, 'index']);

// Single product detail (slug se)
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Sab categories
Route::get('/categories', [ProductController::class, 'categories']);

// Cart totals calculate karo (mobile app apna local cart yahan bhejega)
Route::post('/cart/calculate', [CartController::class, 'calculate']);

// Order place karo
Route::post('/checkout', [CheckoutController::class, 'store']);

// Order track karo (order number se)
Route::get('/orders/{order_number}', [OrderController::class, 'show']);
