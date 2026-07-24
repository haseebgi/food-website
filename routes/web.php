<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\RiderLocationController;

/*
|--------------------------------------------------------------------------
| Public / Storefront Routes
|--------------------------------------------------------------------------
*/

Route::get('/order/invoice/{order_number}', [CheckoutController::class, 'invoice'])->name('order.invoice');

Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.process');
Route::get('/order-success/{order_number}', [CheckoutController::class, 'success'])->name('order.success');

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [StorefrontController::class, 'product'])->name('product');
Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StorefrontController::class, 'storeCheckout'])->name('checkout.store');
Route::get('/cart', [StorefrontController::class, 'cart'])->name('cart');
Route::view('/about', 'frontend.about')->name('about');
Route::view('/contact', 'frontend.contact')->name('contact');

// add to cart
Route::post('/add-to-cart', [StorefrontController::class, 'addToCart'])->name('cart.add');
Route::post('/update-cart', [StorefrontController::class, 'updateCart'])->name('cart.update');
Route::post('/remove-from-cart', [StorefrontController::class, 'removeFromCart'])->name('cart.remove');

// Order tracking (public rakha hai, customer/rider link se access karte hain)
Route::get('/order/track/{order_number}', [CustomerDashboardController::class, 'trackOrder'])->name('order.track');
Route::get('/rider/track/{order_number}', [RiderLocationController::class, 'show'])->name('rider.track');
Route::post('/rider/track/{order_number}/update', [RiderLocationController::class, 'update'])->name('rider.track.update');
Route::get('/order/location/{order_number}', [CustomerDashboardController::class, 'riderLocation'])->name('order.location');

// Force GET Logout Link (Zero Form/JS Dependency)
Route::get('/force-logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
})->name('force.logout');


/*
|--------------------------------------------------------------------------
| Authenticated Routes — koi bhi logged-in user (customer ya admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // 💡 Route name ko 'account' kar diya taake links crash na hon
    Route::get('/account', [CustomerDashboardController::class, 'index'])->name('account');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
});


/*
|--------------------------------------------------------------------------
| Admin-only Routes — sirf role_id = 1 wale users
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    // Generic dashboard route (pehle bina role-check khula hua tha, ab protected hai)
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Admin dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Expenses Module Routes
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::resource('expenses', ExpenseController::class);

    // Payment route
    Route::get('payments/create/{order_id}', [PaymentController::class, 'create'])->name('payments.create_with_order');
    Route::resource('payments', PaymentController::class);

    // --- Order Routes ---
    // PDF Routes (Resource se pehle rakhein taake conflict na ho)
    Route::get('/orders/download-pdf', [OrderController::class, 'downloadPdf'])->name('orders.downloadPdf');
    Route::get('/orders/download-all-pdf', [OrderController::class, 'downloadAllPdf'])->name('orders.downloadAllPdf');
    Route::get('/orders/{id}/download-pdf', [OrderController::class, 'downloadSinglePdf'])->name('orders.downloadSinglePdf');
    Route::get('/orders/{id}/update-status/{status}', [OrderController::class, 'quickUpdateStatus'])->name('orders.quickUpdate');
    Route::get('/orders/{order}/rider-qr', [OrderController::class, 'riderQr'])->name('orders.riderQr');
    Route::resource('orders', OrderController::class);

    // Product route
    Route::resource('products', ProductController::class);

    // Customer route
    Route::resource('customers', CustomerController::class);

    // User route
    Route::resource('users', UserController::class);

    // Supplier route
    Route::resource('suppliers', SupplierController::class);

    // Stocktransaction route
    Route::resource('stock-transactions', StockTransactionController::class);

    // Purchase route
    Route::resource('purchases', PurchaseController::class);

    // Category route
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/auth.php';
