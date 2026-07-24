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

/*
|--------------------------------------------------------------------------
| Public / Storefront Routes (koi bhi access kar sakta hai)
|--------------------------------------------------------------------------
*/

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [StorefrontController::class, 'product'])->name('product');
Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StorefrontController::class, 'storeCheckout'])->name('checkout.store');
Route::get('/cart', [StorefrontController::class, 'cart'])->name('cart');
Route::view('/about', 'frontend.about')->name('about');
Route::view('/contact', 'frontend.contact')->name('contact');

Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.process');
Route::get('/order-success/{order_number}', [CheckoutController::class, 'success'])->name('order.success');
Route::get('/order/invoice/{order_number}', [CheckoutController::class, 'invoice'])->name('order.invoice');

// Cart AJAX
Route::post('/add-to-cart', [StorefrontController::class, 'addToCart'])->name('cart.add');
Route::post('/update-cart', [StorefrontController::class, 'updateCart'])->name('cart.update');
Route::post('/remove-from-cart', [StorefrontController::class, 'removeFromCart'])->name('cart.remove');

// Order tracking (customer / rider ke liye public rakha)
Route::get('/order/track/{order_number}', [CustomerDashboardController::class, 'trackOrder'])->name('order.track');
Route::get('/rider/track/{order_number}', [App\Http\Controllers\RiderLocationController::class, 'show'])->name('rider.track');
Route::post('/rider/track/{order_number}/update', [App\Http\Controllers\RiderLocationController::class, 'update'])->name('rider.track.update');
Route::get('/order/location/{order_number}', [CustomerDashboardController::class, 'riderLocation'])->name('order.location');

// Force logout (kisi ko bhi allow, session hi khatam kar raha hai)
Route::get('/force-logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
})->name('force.logout');


/*
|--------------------------------------------------------------------------
| Authenticated (logged-in) Routes — koi bhi logged-in user
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

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

    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Expenses
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::resource('expenses', ExpenseController::class);

    // Payments
    Route::get('payments/create/{order_id}', [PaymentController::class, 'create'])->name('payments.create_with_order');
    Route::resource('payments', PaymentController::class);

    // Orders (PDF routes resource se pehle)
    Route::get('/orders/download-pdf', [OrderController::class, 'downloadPdf'])->name('orders.downloadPdf');
    Route::get('/orders/download-all-pdf', [OrderController::class, 'downloadAllPdf'])->name('orders.downloadAllPdf');
    Route::get('/orders/{id}/download-pdf', [OrderController::class, 'downloadSinglePdf'])->name('orders.downloadSinglePdf');
    Route::get('/orders/{id}/update-status/{status}', [OrderController::class, 'quickUpdateStatus'])->name('orders.quickUpdate');
    Route::get('/orders/{order}/rider-qr', [OrderController::class, 'riderQr'])->name('orders.riderQr');
    Route::resource('orders', OrderController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Users
    Route::resource('users', UserController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Stock Transactions
    Route::resource('stock-transactions', StockTransactionController::class);

    // Purchases
    Route::resource('purchases', PurchaseController::class);

    // Categories
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/auth.php';
