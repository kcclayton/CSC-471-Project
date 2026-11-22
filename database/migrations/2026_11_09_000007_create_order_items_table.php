<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->foreignId('order_id')
                  ->constrained('orders', 'order_id')
                  ->cascadeOnDelete();
            $table->foreignId('product_id')
                  ->constrained('products', 'product_id')
                  ->restrictOnDelete();               // retire products with is_active instead
            $table->string('sku_at_purchase');
            $table->decimal('unit_price', 10, 0);
            $table->integer('quantity');
            $table->decimal('line_total', 10, 0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
