<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * The logged-in user: one row from the users table, fetched with the Query Builder.
 *
 * Laravel's login system needs to know which column is the ID (user_id) and which
 * holds the password (pwrd_hash); this class tells it. Any other column can be read
 * as a property, for example Auth::user()->first_name or Auth::user()->is_admin.
 */
class ShopUser implements Authenticatable
{
    /**
     * @param  object{user_id: int, email: string, pwrd_hash: string, first_name: string, last_name: ?string, is_admin: bool, created_at: string}  $row
     */
    public function __construct(private object $row) {}

    public function getAuthIdentifierName(): string
    {
        return 'user_id';
    }

    public function getAuthIdentifier(): int
    {
        return $this->row->user_id;
    }

    public function getAuthPasswordName(): string
    {
        return 'pwrd_hash';
    }

    public function getAuthPassword(): string
    {
        return $this->row->pwrd_hash;
    }

    /**
     * The users table has no remember_token column, so "remember me" is not supported.
     */
    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken(mixed $value): void
    {
        //
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    /**
     * Read a column from the user's row. The password hash is never exposed this way.
     */
    public function __get(string $column): mixed
    {
        if ($column === 'pwrd_hash') {
            return null;
        }

        return $this->row->{$column} ?? null;
    }

    public function __isset(string $column): bool
    {
        return $column !== 'pwrd_hash' && isset($this->row->{$column});
    }
}
