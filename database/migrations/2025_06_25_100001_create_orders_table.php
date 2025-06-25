<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate->Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint ) {
            ->id();
            ->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            ->foreignId('user_id')->constrained('users')->onDelete('cascade');
            ->string('customer_name');
            ->decimal('total_amount', 10, 2);
            ->enum('status', ['pending', 'confirmed', 'shipped', 'completed', 'cancelled'])->default('pending');
            ->timestamps();

            ->index(['tenant_id', 'user_id']);
            ->index('status');
        });

        Schema::create('order_items', function (Blueprint ) {
            ->id();
            ->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            ->foreignId('product_id')->constrained('products')->onDelete('cascade');
            ->integer('quantity');
            ->decimal('price_per_unit', 10, 2);
            ->timestamps();

            ->index(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
