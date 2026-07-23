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

Route::patch('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');



Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');


Route::get('/order/invoice/{order_number}', [CheckoutController::class, 'invoice'])->name('order.invoice');

Route::post('/checkout', [StorefrontController::class, 'storeCheckout'])->name('checkout.store');

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



// 💡 Route name ko 'account' kar diya taake links crash na hon
Route::get('/account', [App\Http\Controllers\CustomerDashboardController::class, 'index'])->name('account');

//add to cart
Route::post('/add-to-cart', [StorefrontController::class, 'addToCart'])->name('cart.add');
Route::post('/update-cart', [StorefrontController::class, 'updateCart'])->name('cart.update');
Route::post('/remove-from-cart', [StorefrontController::class, 'removeFromCart'])->name('cart.remove');




Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

});
// Expenses Module Routes
Route::resource('expense-categories', ExpenseCategoryController::class);
Route::resource('expenses', ExpenseController::class);

//Payment route
Route::get('payments/create/{order_id}', [PaymentController::class, 'create'])->name('payments.create_with_order');
Route::resource('payments', PaymentController::class);

//order route

// --- Order Routes ---

// PDF Routes (Resource se pehle rakhein taake conflict na ho)
Route::get('/orders/download-pdf', [OrderController::class, 'downloadPdf'])->name('orders.downloadPdf');
Route::get('/orders/download-all-pdf', [OrderController::class, 'downloadAllPdf'])->name('orders.downloadAllPdf');
Route::get('/orders/{id}/download-pdf', [OrderController::class, 'downloadSinglePdf'])->name('orders.downloadSinglePdf');

// Resource Route
Route::get('/orders/{id}/update-status/{status}', [OrderController::class, 'quickUpdateStatus'])->name('orders.quickUpdate');
Route::resource('orders', OrderController::class);
// Ye line apni routes/web.php mein add karein
Route::get('/order/track/{order_number}', [CustomerDashboardController::class, 'trackOrder'])->name('order.track');

Route::get('/orders/{order}/rider-qr', [App\Http\Controllers\OrderController::class, 'riderQr'])->name('orders.riderQr');




//product route
Route::resource('products', ProductController::class);


//customer route
Route::resource('customers', CustomerController::class);


//user route
Route::resource('users', UserController::class);

//Supplier route
Route::resource('suppliers', SupplierController::class);

//Stocktransaction route
Route::resource('stock-transactions', StockTransactionController::class);


//purchase route
Route::resource('purchases', PurchaseController::class);


//pdf route
// Individual order download ke liye
Route::get('/orders/{id}/download-pdf', [App\Http\Controllers\OrderController::class, 'downloadSinglePdf'])->name('orders.downloadSinglePdf');




Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

//live location routes

use App\Http\Controllers\RiderLocationController;

// Rider ka page (WhatsApp/SMS pe ye link rider ko bhej dena)
Route::get('/rider/track/{order_number}', [RiderLocationController::class, 'show'])->name('rider.track');

// Rider ka browser is par location bhejta rahega (AJAX)
Route::post('/rider/track/{order_number}/update', [RiderLocationController::class, 'update'])->name('rider.track.update');

// Customer ka page is se rider ki location poll karega (AJAX)
Route::get('/order/location/{order_number}', [App\Http\Controllers\CustomerDashboardController::class, 'riderLocation'])->name('order.location');





// 💡 Force GET Logout Link (Zero Form/JS Dependency)
Route::get('/force-logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home'); // logout ke baad storefront/home par bhej dega
})->name('force.logout');

require __DIR__.'/auth.php';