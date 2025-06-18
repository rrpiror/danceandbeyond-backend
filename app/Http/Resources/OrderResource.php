<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class OrderResource extends JsonResource
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
            'user_id' => $this->user_id,
            'payment_method_id' => $this->payment_method_id,
            'amount' => $this->amount,
            'payment_confirmed' => $this->payment_confirmed,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Decode and include addresses
            'addresses' => json_decode($this->addresses),
            
            // Transform seller orders with seller information and decoded product snapshots
            'seller_orders' => $this->sellerOrders->map(function ($sellerOrder) {                
                return new SellerOrderResource($sellerOrder);
            }),
        ];
    }
}
