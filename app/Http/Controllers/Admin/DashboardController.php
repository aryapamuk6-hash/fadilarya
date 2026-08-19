<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellerApplication;
use App\Models\Store;
use App\Models\TopUp;
use App\Models\User;
use App\Models\Withdrawal;
use Inertia\Inertia;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'sellers' => User::where('role', 'seller')->count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => OrderItem::sum(\DB::raw('price * quantity')),
            'pendingTopups' => Schema::hasTable('top_ups') ? TopUp::where('status', 'pending')->count() : 0,
            'pendingSellerApplications' => Schema::hasTable('seller_applications') ? SellerApplication::where('seller_status', 'pending')->count() : 0,
            'soldOutProducts' => Product::where('status', 'sold_out')->count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }

    public function statistics()
    {
        $stats = [
            'users' => User::count(),
            'sellers' => User::where('role', 'seller')->count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => OrderItem::sum(\DB::raw('price * quantity')),
            'pendingTopups' => Schema::hasTable('top_ups') ? TopUp::where('status', 'pending')->count() : 0,
            'pendingSellerApplications' => Schema::hasTable('seller_applications') ? SellerApplication::where('seller_status', 'pending')->count() : 0,
            'soldOutProducts' => Product::where('status', 'sold_out')->count(),
        ];

        $topSeller = Store::orderByDesc('total_sales')->first();
        $topProduct = Product::orderByDesc('sold_count')->first();

        $latestOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        $salesChart = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $totalCommission = Order::sum('commission_amount');

        return Inertia::render('Admin/Statistics', [
            'stats' => $stats,
            'topSeller' => $topSeller,
            'topProduct' => $topProduct,
            'latestOrders' => $latestOrders,
            'salesChart' => $salesChart,
            'totalCommission' => $totalCommission,
        ]);
    }
}

