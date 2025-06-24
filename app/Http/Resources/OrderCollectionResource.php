<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\User;

class OrderCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Extract all seller IDs from all orders and seller orders
        $sellerIds = $this->collection->map(function ($order) {
            return $order->sellerOrders->pluck('seller_id')->unique()->toArray();
        })->flatten()->unique()->toArray();
        
        // Fetch all sellers in one query for efficiency
        $sellers = User::whereIn('id', $sellerIds)->select('id', 'name', 'email')->get();
        
        return $this->collection->map(function ($order) use ($sellers) {
                return [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_method_id' => $order->payment_method_id,
                    'amount' => $order->amount,
                    'payment_confirmed' => $order->payment_confirmed,
                    'status' => $order->status,
                    'deleted_at' => $order->deleted_at,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    
                    // Decode and include addresses
                    'addresses' => json_decode($order->addresses),
                    
                    // Transform seller orders with seller information and decoded product snapshots
                    'seller_orders' => $order->sellerOrders->map(function ($sellerOrder) {                    
                        return new SellerOrderResource($sellerOrder);
                    }),
                    
                    // Include stripe intent if available
                    'stripe_intent' => $order->stripeIntent ? [
                        'id' => $order->stripeIntent->id,
                        'payment_intent_id' => $order->stripeIntent->payment_intent_id,
                        'client_secret' => $order->stripeIntent->client_secret,
                        'amount' => $order->stripeIntent->amount,
                        'currency' => $order->stripeIntent->currency,
                        'status' => $order->stripeIntent->status,
                        'created_at' => $order->stripeIntent->created_at,
                        'updated_at' => $order->stripeIntent->updated_at,
                    ] : null,
                ];
            })->all();
    }
}
