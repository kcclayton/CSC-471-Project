<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\DB;

/**
 * Looks up users for Laravel's login system (Auth::attempt, Auth::user, the auth
 * middleware) with Query Builder queries on the users table, so no Eloquent model
 * is needed.
 */
class QueryBuilderUserProvider implements UserProvider
{
    public function __construct(private Hasher $hasher) {}

    /**
     * Find a user by ID. Laravel calls this on every request for whoever is logged in.
     */
    public function retrieveById(mixed $identifier): ?ShopUser
    {
        $row = DB::table('users')->where('user_id', $identifier)->first();

        return $row === null ? null : new ShopUser($row);
    }

    /**
     * "Remember me" isn't supported because the users table has no remember_token column.
     */
    public function retrieveByToken(mixed $identifier, #[\SensitiveParameter] mixed $token): ?ShopUser
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, #[\SensitiveParameter] mixed $token): void
    {
        //
    }

    /**
     * Find the user matching the email address typed into the login form.
     *
     * @param  array{email?: string, password?: string}  $credentials
     */
    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials): ?ShopUser
    {
        if (empty($credentials['email'])) {
            return null;
        }

        $row = DB::table('users')->where('email', $credentials['email'])->first();

        return $row === null ? null : new ShopUser($row);
    }

    /**
     * Check the typed password against the stored hash.
     *
     * @param  array{email?: string, password?: string}  $credentials
     */
    public function validateCredentials(Authenticatable $user, #[\SensitiveParameter] array $credentials): bool
    {
        if (! isset($credentials['password'])) {
            return false;
        }

        return $this->hasher->check($credentials['password'], $user->getAuthPassword());
    }

    /**
     * Re-hash the password after a successful login if the hashing settings have changed.
     *
     * @param  array{email?: string, password?: string}  $credentials
     */
    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false): void
    {
        if (! $force && ! $this->hasher->needsRehash($user->getAuthPassword())) {
            return;
        }

        DB::table('users')
            ->where('user_id', $user->getAuthIdentifier())
            ->update(['pwrd_hash' => $this->hasher->make($credentials['password'])]);
    }
}
