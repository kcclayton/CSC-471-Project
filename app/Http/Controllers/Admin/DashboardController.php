<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Store totals, low-stock alerts and the latest orders.
     */
    public function index(): View
    {
        $threshold = (int) config('shop.low_stock_threshold');

        return view('admin.dashboard', [
            'revenue' => (int) DB::table('orders')->where('status', '!=', 'cancelled')->sum('total'),
            'orderCount' => DB::table('orders')->count(),
            'pendingCount' => DB::table('orders')->where('status', 'pending')->count(),
            'productCount' => DB::table('products')->where('is_active', true)->count(),
            'customerCount' => DB::table('users')->where('is_admin', false)->count(),
            'threshold' => $threshold,
            'lowStock' => DB::table('products')
                ->where('is_active', true)
                ->where('stock_qty', '<=', $threshold)
                ->orderBy('stock_qty')
                ->orderBy('name')
                ->get(['product_id', 'sku', 'name', 'stock_qty']),
            'recentOrders' => DB::table('orders')
                ->join('users', 'users.user_id', '=', 'orders.user_id')
                ->orderByDesc('orders.order_date')
                ->orderByDesc('orders.order_id')
                ->limit(5)
                ->get(['orders.order_id', 'orders.order_date', 'orders.status', 'orders.total', 'users.first_name', 'users.last_name']),
        ]);
    }
}