<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'product_id', 'sku_at_purchase', 'unit_price', 'quantity', 'line_total',
    ];

    protected function casts(): array
    {
        return ['unit_price' => 'decimal:2', 'line_total' => 'decimal:2'];
    }

    public function order()   { return $this->belongsTo(Order::class, 'order_id', 'order_id'); }
    public function product() { return $this->belongsTo(Product::class, 'product_id', 'product_id'); }
}
