<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private CartService $cart) {}

    /**
     * List every user with how many orders they've placed and how much they've spent.
     * The password hash is never selected.
     */
    public function index(): View
    {
        $users = DB::table('users')
            ->leftJoin('orders', 'orders.user_id', '=', 'users.user_id')
            ->groupBy('users.user_id')
            ->orderBy('users.user_id')
            ->select([
                'users.user_id',
                'users.email',
                'users.first_name',
                'users.last_name',
                'users.is_admin',
                'users.created_at',
                'users.avatar_url',
            ])
            ->selectRaw('COUNT(orders.order_id) as order_count')
            ->selectRaw("COALESCE(SUM(orders.total) FILTER (WHERE orders.status <> 'cancelled'), 0) as total_spent")
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show one user's details, cart size and order history, or a 404 page.
     */
    public function show(int $id): View
    {
        $user = DB::table('users')
            ->select('user_id', 'email', 'first_name', 'last_name', 'is_admin', 'created_at', 'avatar_url')
            ->where('user_id', $id)
            ->first();

        abort_if($user === null, 404);

        $orders = DB::table('orders')
            ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.order_id')
            ->where('orders.user_id', $id)
            ->groupBy('orders.order_id')
            ->orderByDesc('orders.order_date')
            ->orderByDesc('orders.order_id')
            ->select('orders.order_id', 'orders.order_date', 'orders.status', 'orders.total')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as item_count')
            ->get();

        return view('admin.users.show', [
            'user' => $user,
            'orders' => $orders,
            'totalSpent' => $orders->where('status', '!=', 'cancelled')->sum('total'),
            'cartCount' => $this->cart->itemCount($id),
        ]);
    }
}
