<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id');
            $table->foreignId('user_id')
                  ->unique()                          // FK + UK: one cart per user
                  ->constrained('users', 'user_id')
                  ->cascadeOnDelete();
            $table->timestamps();                     // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
