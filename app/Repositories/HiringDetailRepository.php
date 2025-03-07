<?php

namespace App\Repositories;

use App\Models\HiringDetail;

class HiringDetailRepository
{
    protected HiringDetail $hiringDetail;

    public function __construct(HiringDetail $hiringDetail)
    {
        $this->hiringDetail = $hiringDetail;
    }

    public function findById($id)
    {
        return $this->hiringDetail->find($id);
    }

    public function findByProductId($productId)
    {
        return $this->hiringDetail->where('product_id', $productId)->first();
    }

    public function create(array $data)
    {
        return $this->hiringDetail->create($data);
    }

    public function updateOrCreate(array $data)
    {
        return $this->hiringDetail->updateOrCreate(['product_id' => $data['product_id']], $data);
    }

    public function deleteByProductId($productId)
    {
        return $this->hiringDetail->where('product_id', $productId)->delete();
    }
}
