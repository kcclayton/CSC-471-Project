<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        $avatar = DB::table('users')->where('user_id', $userId)->value('avatar_url');

        Auth::logout();
        DB::table('users')->where('user_id', $userId)->delete();
            if ($avatar !== null) {
                Storage::disk('public')->delete($avatar);
            }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted.');
    }

    /**
     * Save a new profile picture and delete the old file.
     */
    public function updateAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        $userId = Auth::id();
        $oldPath = DB::table('users')->where('user_id', $userId)->value('avatar_url');

        $path = $request->file('avatar')->store('avatars', 'public');
        DB::table('users')->where('user_id', $userId)->update(['avatar_url' => $path]);

        if ($oldPath !== null) {
            Storage::disk('public')->delete($oldPath);
        }

        return back()->with('status', 'Your profile picture has been updated.');
    }

    /**
     * Go back to the default profile picture.
     */
    public function destroyAvatar(): RedirectResponse
    {
        $userId = Auth::id();
        $oldPath = DB::table('users')->where('user_id', $userId)->value('avatar_url');

        if ($oldPath !== null) {
            DB::table('users')->where('user_id', $userId)->update(['avatar_url' => null]);
            Storage::disk('public')->delete($oldPath);
        }

        return back()->with('status', 'Your profile picture is back to the default.');
    }
}
