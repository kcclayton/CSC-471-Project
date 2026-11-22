<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Junction table: which seasons each product belongs to (one or more).
     */
    public function up(): void
    {
        Schema::create('product_seasons', function (Blueprint $table) {
            $table->foreignId('product_id')
                  ->constrained('products', 'product_id')
                  ->cascadeOnDelete();                // a deleted product's season links go with it
            $table->foreignId('season_id')
                  ->constrained('seasons', 'season_id')
                  ->restrictOnDelete();               // can't delete a season that still has products

            $table->primary(['product_id', 'season_id']);   // each pairing only once
            $table->index('season_id');                       // fast "products in this season" lookups
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_seasons');
    }
};