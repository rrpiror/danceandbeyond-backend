<?php

namespace App\Repositories;

use App\Models\ProductFulfillmentOption;

class ProductFulfillmentOptionRepository
{
    protected ProductFulfillmentOption $productFulfillmentOption;

    public function __construct(ProductFulfillmentOption $productFulfillmentOption)
    {
        $this->productFulfillmentOption = $productFulfillmentOption;
    }

    public function create(array $data)
    {
        return $this->productFulfillmentOption->create($data);
    }

    public function deleteByProductId($id)
    {
        return $this->productFulfillmentOption->where('product_id', $id)->delete();
    }
}
