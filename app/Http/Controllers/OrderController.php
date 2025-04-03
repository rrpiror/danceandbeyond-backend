<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected ValidationService $validationService;
    private const CREATE_RULES = [
        'payment_method_id' => 'required|integer',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.size_id' => 'required|integer|exists:sizes,id',
        'billing_address_id' => 'required|integer|exists:addresses,id',
        'shipping_address_id' => 'required|integer|exists:addresses,id',
    ];

    public function __construct(OrderService $orderService, ValidationService $validationService)
    {
        $this->orderService = $orderService;
        $this->validationService = $validationService;
    }

    public function index()
    {
        try {
            $orders = $this->orderService->getAll();
            return apiResponse(true, $orders, "Orders retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, $ex->getCode() ?? 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validation = $this->validationService->validate($request, self::CREATE_RULES);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $order = $this->orderService->create($request->all());

            return apiResponse(true, $order, "Order placed");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, $ex->getCode() ?? 500);
        }
    }
}
