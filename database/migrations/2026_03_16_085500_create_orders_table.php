<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users');

            $table->foreignId('store_id')->constrained();

            $table->integer('product_total');
            $table->integer('delivery_fee')->default(0);
            $table->integer('platform_fee')->default(2000);

            $table->integer('total_price');

            $table->enum('order_type', ['pickup', 'delivery']);

            $table->enum('status', [
                'pending',
                'accepted',
                'preparing',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
