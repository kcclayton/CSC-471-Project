<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * List the logged-in user's orders, newest first, with how many items each has.
     */
    public function index(): View
    {
        $orders = DB::table('orders')
            ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.order_id')
            ->where('orders.user_id', Auth::id())
            ->groupBy('orders.order_id')
            ->orderByDesc('orders.order_date')
            ->orderByDesc('orders.order_id')
            ->select('orders.order_id', 'orders.order_date', 'orders.status', 'orders.total')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as item_count')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show one of the user's orders. Other users' orders give a 404, as if they don't exist.
     */
    public function show(int $id): View
    {
        $order = DB::table('orders')
            ->where('order_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        abort_if($order === null, 404);

        $items = DB::table('order_items')
            ->join('products', 'products.product_id', '=', 'order_items.product_id')
            ->where('order_items.order_id', $id)
            ->select(
                'order_items.order_item_id', 'order_items.sku_at_purchase', 'order_items.unit_price',
                'order_items.quantity', 'order_items.line_total',
                'products.product_id', 'products.name', 'products.image_url', 'products.is_active'
            )
            ->orderBy('order_items.order_item_id')
            ->get();

        return view('orders.show', compact('order', 'items'));
    }
}
