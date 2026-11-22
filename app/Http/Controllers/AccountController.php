<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Show the account page with all three forms.
     */
    public function edit(): View
    {
        return view('account.edit', ['user' => Auth::user()]);
    }

    /**
     * Save a new name and email.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::table('users')
            ->where('user_id', Auth::id())
            ->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
            ]);

        return back()->with('status', 'Your details have been saved.');
    }

    /**
     * Save a new password, hashed.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        DB::table('users')
            ->where('user_id', Auth::id())
            ->update(['pwrd_hash' => Hash::make($request->validated('password'))]);

        return back()->with('status', 'Your password has been changed.');
    }

    /**
     * Delete the account, unless the user has orders.
     */
    public function destroy(DeleteAccountRequest $request): RedirectResponse
    {
        $userId = Auth::id();

        if (DB::table('orders')->where('user_id', $userId)->exists()) {
            return back()->withErrors(
                ['password' => 'Accounts with order history can\'t be deleted.'],
                'deleteAccount'
            );
        }

        Auth::logout();
        DB::table('users')->where('user_id', $userId)->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted.');
    }
}
