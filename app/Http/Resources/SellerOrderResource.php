<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerOrderResource extends JsonResource
{
    private function decodeJsonField($value)
    {
        if (is_array($value) || is_object($value) || $value === null) {
            return $value;
        }

        return json_decode($value);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'seller_id' => $this->seller_id,
            'amount' => $this->amount,
            'delivery_charge' => $this->delivery_charge ?? 0,
            'transferred_at' => $this->transferred_at,
            'statuses' => $this->statuses->sortBy('pivot.created_at')->values(),
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Include buyer information from the related order
            'buyer' => [
                'id' => $this->order->user->id,
                'name' => $this->order->user->name,
                'email' => $this->order->user->email,
                'phone_number' => $this->order->user->phone_number ?? null,
            ],

            'seller' => [
                'id' => $this->seller->id,
                'name' => $this->seller->name,
                'email' => $this->seller->email,
                'phone_number' => $this->seller->phone_number ?? null,
            ],
            
            // Decode and include addresses from the order
            'addresses' => $this->decodeJsonField($this->order->addresses),
            
            // Transform order items with decoded product snapshots
            'order_items' => $this->orderItems->map(function ($orderItem) {
                return [
                    'id' => $orderItem->id,
                    'variant' => $orderItem->variant,
                    'seller_order_id' => $orderItem->seller_order_id,
                    'product_id' => $orderItem->product_id,
                    'quantity' => $orderItem->quantity,
                    'price' => $orderItem->price,
                    'product_snapshot' => $this->decodeJsonField($orderItem->product_snapshot),
                    'deleted_at' => $orderItem->deleted_at,
                    'created_at' => $orderItem->created_at,
                    'updated_at' => $orderItem->updated_at,
                    'hiring_detail' => $orderItem->hiringDetail,
                ];
            }),
        ];
    }
}
