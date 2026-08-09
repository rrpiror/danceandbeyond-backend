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
        'orderItems.hiringDetail',
        'orderItems.variant.size',
        'orderItems.variant.colour',
        'order',
        'order.stripeIntent',
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
        $holdDays = (int) env('PAYOUT_HOLD_DAYS', 14);

        return $this->sellerOrder
            ->with(['seller', 'statuses', 'order.stripeIntent'])
            ->whereNull('transferred_at')
            ->whereHas('order.stripeIntent', function ($query) {
                $query->where('status', 'succeeded');
            })
            ->whereHas('statuses', function ($query) {
                $query->where('name', 'Payment Confirmed');
            })
            ->where(function ($query) use ($holdDays) {
                $query
                    ->where(function ($saleQuery) use ($holdDays) {
                        $saleQuery
                            ->whereDoesntHave('orderItems.hiringDetail')
                            ->whereHas('statuses', function ($statusQuery) use ($holdDays) {
                                $statusQuery
                                    ->where('name', 'Delivered')
                                    ->where('seller_order_statuses.created_at', '<=', now()->subDays($holdDays));
                            });
                    })
                    ->orWhere(function ($hireQuery) use ($holdDays) {
                        $hireQuery
                            ->whereHas('orderItems.hiringDetail')
                            ->whereHas('statuses', function ($statusQuery) use ($holdDays) {
                                $statusQuery
                                    ->where('name', 'Completed')
                                    ->where('seller_order_statuses.created_at', '<=', now()->subDays($holdDays));
                            });
                    });
            })
            ->get();
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
        $sellerOrders = $this->sellerOrder
            ->where('seller_id', $sellerId)
            ->latest()
            ->get()
            ->load($this->detailedRelations);
        
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
