<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    const UPDATED_AT = null;                 // table only has created_at

    protected $fillable = ['email', 'pwrd_hash', 'first_name', 'last_name', 'is_admin'];
    protected $hidden = ['pwrd_hash'];

    protected function casts(): array
    {
        return [
            'pwrd_hash' => 'hashed',         // plain text assigned here gets hashed automatically
            'is_admin'  => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    // Tell Laravel's login system the password lives in pwrd_hash, not "password"
    public function getAuthPasswordName()
    {
        return 'pwrd_hash';
    }

    // No remember_token column in this schema, so "remember me" is turned off
    public function getRememberTokenName()
    {
        return '';
    }

    public function cart()   { return $this->hasOne(Cart::class, 'user_id', 'user_id'); }
    public function orders() { return $this->hasMany(Order::class, 'user_id', 'user_id'); }
}
