<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'sku', 'name', 'description', 'price', 'image_url',
        'category_id', 'stock_qty', 'is_active',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'is_active' => 'boolean'];
    }

    public function category()          { return $this->belongsTo(Category::class, 'category_id', 'category_id'); }
    // named productAttributes because Eloquent models already use "attributes" internally
    public function productAttributes() { return $this->hasMany(ProductAttribute::class, 'product_id', 'product_id'); }
    public function cartItems()         { return $this->hasMany(CartItem::class, 'product_id', 'product_id'); }
    public function orderItems()        { return $this->hasMany(OrderItem::class, 'product_id', 'product_id'); }
}
