<?php

namespace App\Repositories;

use App\Models\SellerOrder;
use App\Http\Resources\SellerOrderResource;
use App\Http\Resources\SellerOrderCollectionResource;

class SellerOrderRepository
{
    protected SellerOrder $sellerOrder;

    public $detailedRelations = [
        'orderItems',
        'orderItems.variant.size',
        'orderItems.variant.colour',
        'order',
        'order.user:id,name,email,phone_number',
        'seller:id,name,email,phone_number',
        'statuses',
    ];

    public function __construct(SellerOrder $sellerOrder)
    {
        $this->sellerOrder = $sellerOrder;
    }

    public function create(array $data)
    {
        return $this->sellerOrder->create($data);
    }

    public function findOldOrders()
    {
        return $this->sellerOrder->with('seller')->where('created_at', '<=', now()->subDays(14))->whereNull('transferred_at')->where('status', 'completed')->get();
    }
    public function findByOrderId($orderId)
    {
        return $this->sellerOrder->with('statuses')->where('order_id', $orderId)->latest()->get();
    }

    public function findSalesProductsBySeller($sellerId)
    {
        return $this->sellerOrder
            ->where('seller_id', $sellerId)
            ->whereHas('orderItems.product', function ($query) {
                $query->where('type', 'sale');
            })
            ->latest()
            ->get();
    }

    public function findHireProductsBySeller($sellerId)
    {
        return $this->sellerOrder
            ->where('seller_id', $sellerId)
            ->whereHas('orderItems.product', function ($query) {
                $query->where('type', 'hire');
            })
            ->latest()
            ->get();
    }

    public function findAllSellerOrders($sellerId)
    {
        $sellerOrders = $this->sellerOrder->where('seller_id', $sellerId)->latest()->get()->load($this->detailedRelations);
        
        return new SellerOrderCollectionResource($sellerOrders);
    }

    public function findById($id)
    {
        $sellerOrder = $this->sellerOrder->find($id)->load($this->detailedRelations);
        
        return new SellerOrderResource($sellerOrder);
    }

    public function findByIdRaw($id)
    {
        return $this->sellerOrder->find($id);
    }
}