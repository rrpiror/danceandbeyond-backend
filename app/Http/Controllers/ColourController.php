<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\ProductService;

class ColourController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try {
            $colours = $this->productService->getAllColours();

            return apiResponse(true, $colours);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
