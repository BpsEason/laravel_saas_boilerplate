<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;

class OrderResource extends BaseResource
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
            'user_id' => $this->user_id, # User who placed the order
            'tenant_id' => $this->tenant_id, # Tenant association
            'customer_name' => $this->customer_name,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')), # Include order items
        ];
    }
}
