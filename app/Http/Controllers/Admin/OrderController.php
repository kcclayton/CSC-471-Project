<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private OrderService $orders) {}

    /**
     * List all customers' orders, optionally filtered by status.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        if (! in_array($status, OrderService::STATUSES, true)) {
            $status = null;
        }

        $orders = DB::table('orders')
            ->join('users', 'users.user_id', '=', 'orders.user_id')
            ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.order_id')
            ->when($status, fn ($query) => $query->where('orders.status', $status))
            ->groupBy('orders.order_id', 'users.user_id')
            ->orderByDesc('orders.order_date')
            ->orderByDesc('orders.order_id')
            ->select(
                'orders.order_id', 'orders.order_date', 'orders.status', 'orders.total',
                'users.first_name', 'users.last_name', 'users.email'
            )
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as item_count')
            ->paginate(20)
            ->withQueryString();

        $counts = DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.orders.index', [
            'orders' => $orders,
            'counts' => $counts,
            'status' => $status,
            'statuses' => OrderService::STATUSES,
        ]);
    }

    /**
     * Show any order with its customer, items and the status changes allowed next.
     */
    public function show(int $order): View
    {
        $row = DB::table('orders')
            ->join('users', 'users.user_id', '=', 'orders.user_id')
            ->where('orders.order_id', $order)
            ->select('orders.*', 'users.first_name', 'users.last_name', 'users.email as customer_email')
            ->first();

        abort_if($row === null, 404);

        $items = DB::table('order_items')
            ->join('products', 'products.product_id', '=', 'order_items.product_id')
            ->where('order_items.order_id', $order)
            ->select(
                'order_items.sku_at_purchase', 'order_items.unit_price', 'order_items.quantity',
                'order_items.line_total', 'products.product_id', 'products.name', 'products.image_url'
            )
            ->orderBy('order_items.order_item_id')
            ->get();

        return view('admin.orders.show', [
            'order' => $row,
            'items' => $items,
            'nextStatuses' => OrderService::TRANSITIONS[$row->status] ?? [],
        ]);
    }

    /**
     * Change an order's status, e.g. mark it shipped or cancel it.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $order): RedirectResponse
    {
        $newStatus = $request->validated('status');

        try {
            $this->orders->changeStatus($order, $newStatus);
        } catch (\DomainException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('status', "Order #{$order} marked as {$newStatus}.");
    }
}
