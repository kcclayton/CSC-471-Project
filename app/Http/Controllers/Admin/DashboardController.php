<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show store totals to admins.
     */
    public function index(): View
    {
        return view('admin.dashboard', [
            'userCount'    => DB::table('users')->count(),
            'productCount' => DB::table('products')->count(),
            'orderCount'   => DB::table('orders')->count(),
        ]);
    }
}