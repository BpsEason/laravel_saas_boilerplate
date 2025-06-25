<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint ) {
            ->id();
            ->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            ->foreignId('user_id')->constrained('users')->onDelete('cascade');
            ->string('name');
            ->text('description')->nullable();
            ->decimal('price', 10, 2);
            ->integer('stock')->default(0);
            ->timestamps();

            ->index(['tenant_id', 'user_id']);
            ->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
