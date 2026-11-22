<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('sku')->unique();          // unique added: two products shouldn't share a SKU
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 0);
            $table->string('image_url')->nullable();
            $table->integer('stock_qty')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();                     // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
