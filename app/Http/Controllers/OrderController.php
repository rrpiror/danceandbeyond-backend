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

    public function __construct(OrderService $orderService, ValidationService $validationService)
    {
        $this->orderService = $orderService;
        $this->validationService = $validationService;
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'payment_method_id' => 'required',
                'amount' => 'required',
                'items' => 'required',
                'billing_address.house_number' => 'required',
                'billing_address.street' => 'required',
                'billing_address.city' => 'required',
                'billing_address.postcode' => 'required'
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $order = $this->orderService->create($request->all());

            return apiResponse(true, $order, "Order placed");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'order_id' => 'required',
                'status' => 'required'
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $order = $this->orderService->update($request->all());

            return apiResponse(true, $order, "Order updated");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 500);
        }
    }
}
