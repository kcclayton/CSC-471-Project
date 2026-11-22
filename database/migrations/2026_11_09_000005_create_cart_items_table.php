<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->foreignId('cart_id')
                ->constrained('carts', 'cart_id')
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->constrained('products', 'product_id')
                ->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamp('added_at')->useCurrent();

            $table->unique(['cart_id', 'product_id']); // added: one row per product per cart
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
