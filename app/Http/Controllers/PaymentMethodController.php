<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        try {
            $methods = $this->orderService->getAllPaymentMethods();
            return apiResponse(true, $methods);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
