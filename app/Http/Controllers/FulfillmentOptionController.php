<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;

class FulfillmentOptionController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try {
            $options = $this->productService->getAllFulfillmentOptions();
            return apiResponse(true, $options);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
