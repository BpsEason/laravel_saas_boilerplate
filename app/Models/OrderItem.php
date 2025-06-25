<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate->Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory; # Note: OrderItem doesn't use ForCurrentTenant directly, parent Order handles tenant_id

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_per_unit',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product associated with the order item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
