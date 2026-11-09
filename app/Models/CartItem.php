<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $primaryKey = 'cart_item_id';
    const CREATED_AT = 'added_at';           // table uses added_at instead of created_at
    const UPDATED_AT = null;

    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    public function cart()    { return $this->belongsTo(Cart::class, 'cart_id', 'cart_id'); }
    public function product() { return $this->belongsTo(Product::class, 'product_id', 'product_id'); }
}
