<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    const CREATED_AT = 'order_date';         // order_date is filled in automatically
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'status', 'subtotal', 'tax', 'ship_cost', 'total',
        'ship_name', 'ship_street', 'ship_city', 'ship_state',
        'ship_zip', 'ship_phone', 'ship_email',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'datetime',
            'subtotal'   => 'decimal:2',
            'tax'        => 'decimal:2',
            'ship_cost'  => 'decimal:2',
            'total'      => 'decimal:2',
        ];
    }

    public function user()  { return $this->belongsTo(User::class, 'user_id', 'user_id'); }
    public function items() { return $this->hasMany(OrderItem::class, 'order_id', 'order_id'); }
}
