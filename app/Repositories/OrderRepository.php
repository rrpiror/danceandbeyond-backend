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

    public function getAll($page = 1, $perPage = 15)
    {
        $ordersData = $this->order->where('user_id', Auth::user()->id)
            ->with(['sellerOrders.statuses', 'sellerOrders.orderItems', 'stripeIntent'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);
        
        // Transform the collection using OrderCollectionResource
        $ordersCollection = new OrderCollectionResource($ordersData->getCollection());
        
        // Replace the collection in the paginated data with the transformed collection
        $ordersData->setCollection(collect($ordersCollection->toArray(request())));
        
        return $ordersData;
    }
}