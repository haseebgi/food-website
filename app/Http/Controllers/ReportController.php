<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Default Dates Set Karein (Current Month)
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        $status = $request->input('status');

        // 2. Base Query Filters Ke Sath
        $query = Order::with('customer')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Final Data Table Ke Liye
        $orders = $query->latest()->get();

        // 3. KPI Metrics Cards Data
        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $paidOrders = $orders->where('payment_status', 'Paid')->sum('total_amount');
        $pendingOrders = $orders->where('payment_status', 'Pending')->sum('total_amount');

        // 4. Graph Data — Daily Sales Trend
        $dailySalesData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $graphLabels = $dailySalesData->pluck('date')->toArray();
        $graphTotals = $dailySalesData->pluck('total')->toArray();

        return view('reports.index', compact(
            'orders', 'startDate', 'endDate', 'status',
            'totalSales', 'totalOrders', 'paidOrders', 'pendingOrders',
            'graphLabels', 'graphTotals'
        ));
    }
}