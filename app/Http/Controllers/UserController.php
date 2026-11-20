<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * List every user. The password hash is never selected.
     */
    public function index(): View
    {
        $users = DB::table('users')
            ->select('user_id', 'email', 'first_name', 'last_name', 'is_admin', 'created_at')
            ->orderBy('user_id')
            ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show one user, or a 404 page if no user has that ID.
     */
    public function show(int $id): View
    {
        $user = DB::table('users')
            ->select('user_id', 'email', 'first_name', 'last_name', 'is_admin', 'created_at')
            ->where('user_id', $id)
            ->first();

        abort_if($user === null, 404);

        return view('users.show', compact('user'));
    }
}
