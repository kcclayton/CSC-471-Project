<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('user_id')
                  ->constrained('users', 'user_id')
                  ->restrictOnDelete();               // keep order history
            $table->timestamp('order_date')->useCurrent();
            $table->string('status', 20)->default('pending');
            $table->decimal('subtotal', 10, 0);
            $table->decimal('tax', 10,0)->default(0);
            $table->decimal('ship_cost', 10, 0)->default(0);
            $table->decimal('total', 10, 0);
            $table->string('ship_name');
            $table->string('ship_street');
            $table->string('ship_city');
            $table->string('ship_state', 50);
            $table->string('ship_zip', 20);
            $table->string('ship_phone', 30);
            $table->string('ship_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
