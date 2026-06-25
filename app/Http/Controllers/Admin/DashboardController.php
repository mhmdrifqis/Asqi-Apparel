<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalSales = Order::where('payment_status', 'paid')->where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('is_admin', false)->count();

        // Recent Orders
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Sales data for chart (Last 7 days)
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $salesData['labels'][] = Carbon::parse($date)->format('M d');
            $salesData['data'][] = Order::whereDate('ordered_at', $date)
                ->where('payment_status', 'paid')
                ->where('status', '!=', 'cancelled')
                ->sum('total');
        }

        return view('admin.dashboard', compact(
            'totalSales', 'totalOrders', 'totalProducts', 'totalCustomers', 'recentOrders', 'salesData'
        ));
    }
}
