<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\LocationTrackingService;

class CustomerDashboardController extends Controller
{
    // ✅ Naya add hua - location service inject karne ke liye
    protected $locationService;

    public function __construct(LocationTrackingService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Customer Dashboard Index
     */
    public function index()
    {
        // 1. Agar user login nahi hai, toh login page dikhao
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access your dashboard.');
        }

        // 2. Sirf login user ke orders nikalne hain
        $orders = Order::where('customer_id', auth()->id())
                       ->orderBy('created_at', 'desc')
                       ->with('items') // Efficiency ke liye eager loading
                       ->get();

        return view('frontend.account_dashboard', compact('orders'));
    }

    public function trackOrder($order_number)
{
    // 1. Check user login hai ya nahi
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Please login to track your order.');
    }

    // 2. Order dhoondein jo is customer ka ho
    $order = Order::where('order_number', $order_number)
                  ->where('customer_id', auth()->id())
                  ->with('items.product') // items aur products load karne ke liye
                  ->firstOrFail();

    return view('frontend.track_order', compact('order'));
}

    // ✅ Naya method add hua - customer ka page har kuch second baad
    // ye endpoint call karke rider ki latest location leta rahega (AJAX polling)
    public function riderLocation($order_number)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::where('order_number', $order_number)
                      ->where('customer_id', auth()->id())
                      ->firstOrFail();

        $location = $this->locationService->getLocation($order);

        if (!$location) {
            return response()->json(['available' => false]);
        }

        return response()->json([
            'available'  => true,
            'lat'        => $location['lat'],
            'lng'        => $location['lng'],
            'updated_at' => $location['updated_at'],
            'is_stale'   => $this->locationService->isLocationStale($order),
        ]);
    }
}
