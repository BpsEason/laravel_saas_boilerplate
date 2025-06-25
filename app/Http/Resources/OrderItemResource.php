<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;

class OrderItemResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name, # Assuming product relationship is loaded
            'quantity' => $this->quantity,
            'price_per_unit' => $this->price_per_unit,
            'subtotal' => $this->quantity * $this->price_per_unit,
        ];
    }
}
