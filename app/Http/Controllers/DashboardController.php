<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Expense;
use App\Models\Purchase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Pehle ensure karein ke user login ho
        $middleware('auth');
    }

    public function index(Request $request)
    {
        // Check karein ke agar user ka role_id 1 nahi hai, toh admin dashboard access na mile
        if (auth()->check() && auth()->user()->role_id != 1) {
            return redirect()->route('home')->with('error', 'Aapko admin dashboard access karne ki ijazat nahi hai.');
        }

        // 1. Filter Logic
        $filter = $request->input('filter', 'all');
        $dateRange = $request->input('date_range');

        // 2. Base Queries
        $orderQuery = Order::query();
        $expenseQuery = Expense::query();
        $purchaseQuery = Purchase::query();

        // 3. Apply Filters
        if ($dateRange) {
            // Split "YYYY-MM-DD - YYYY-MM-DD"
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                $start = $dates[0] . ' 00:00:00';
                $end = $dates[1] . ' 23:59:59';
                
                $orderQuery->whereBetween('created_at', [$start, $end]);
                $expenseQuery->whereBetween('created_at', [$start, $end]);
                $purchaseQuery->whereBetween('created_at', [$start, $end]);
            }
        } elseif ($filter == 'week') {
            $orderQuery->where('created_at', '>=', now()->subDays(7));
            $expenseQuery->where('created_at', '>=', now()->subDays(7));
            $purchaseQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($filter == 'month') {
            $orderQuery->where('created_at', '>=', now()->subMonth());
            $expenseQuery->where('created_at', '>=', now()->subMonth());
            $purchaseQuery->where('created_at', '>=', now()->subMonth());
        }

        // 4. Data Fetching
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCustomers = Customer::count();
        $totalSuppliers = Supplier::count();
        $totalUsers = User::count();
        $totalOrders = $orderQuery->count();
        $totalSales = $orderQuery->sum('total_amount');
        $totalExpenses = $expenseQuery->sum('amount');
        $totalPurchases = $purchaseQuery->sum('total_amount');
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'min_stock')->get();

        return view('admin.dashboard.index', compact(
            'totalProducts', 'totalCategories', 'totalCustomers', 'totalSuppliers',
            'totalUsers', 'totalOrders', 'totalSales', 'totalExpenses',
            'totalPurchases', 'lowStockProducts', 'filter'
        ));
    }
}