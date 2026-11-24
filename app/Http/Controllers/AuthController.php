<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Create the acocunt with a Query Builder insert, then log the new user in.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $userId = DB::table('users')->insertGetId([
            'email' => $data['email'],
            'pwrd_hash' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
        ], 'user_id');

        Auth::loginUsingId($userId);
        $request->session()->regenerate();

        return redirect('/products')->with('status', 'Your account was created successfully.');
    }

    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Check the email and password: on success, start a fresh logged-in session.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->validated())) {
            return back()
                ->withErrors(['email' => 'Those details don\'t match an account.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended('/products');

    }

    /**
     * Log out and throw away old session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/products')->with('status', 'You have been logged out successfully.');
    }
}
