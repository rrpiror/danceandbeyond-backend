<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollectionResource;

class OrderRepository
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function findById($id)
    {
        $orderData = $this->order->with('sellerOrders.statuses', 'orderItems.product', 'stripeIntent')->where('user_id', Auth::user()->id)->find($id);
        
        return new OrderResource($orderData);
    }

    public function create(array $data)
    {
        return $this->order->create($data);
    }

    public function getAll($page = 1, $perPage = 15, $filters = [])
    {
        $query = $this->order->where('user_id', Auth::user()->id)
            ->with(['sellerOrders.statuses', 'sellerOrders.orderItems.product', 'stripeIntent']);
        
        // Apply type filter if provided
        if (!empty($filters['type'])) {
            $types = explode(',', $filters['type']);
            
            // Filter orders based on product types in order items
            $query->whereHas('sellerOrders.orderItems.product', function ($q) use ($types) {
                $productTypes = [];
                foreach ($types as $type) {
                    if ($type === 'hire') {
                        $productTypes[] = 'hire';
                    } else if ($type === 'purchase') {
                        $productTypes[] = 'sale';
                    }
                }
                
                if (!empty($productTypes)) {
                    \Log::info('Product types: ' . json_encode($productTypes));
                    $q->whereIn('type', $productTypes);
                }
            });
        }
        
        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'newest';
        if ($sortBy === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        $ordersData = $query->paginate($perPage, ['*'], 'page', $page);
        
        // Transform the collection using OrderCollectionResource
        $ordersCollection = new OrderCollectionResource($ordersData->getCollection());
        
        // Replace the collection in the paginated data with the transformed collection
        $ordersData->setCollection(collect($ordersCollection->toArray(request())));
        
        return $ordersData;
    }
}