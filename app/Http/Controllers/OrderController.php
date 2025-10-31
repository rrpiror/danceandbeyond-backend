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
        // 'payment_method_id' => 'required|integer',
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

    public function index(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 15);
            $sortBy = $request->get('sort_by', 'newest'); // 'newest' or 'oldest'
            $type = $request->get('type', null); // 'hire', 'purchase', or 'hire,purchase'
            
            $filters = [
                'sort_by' => $sortBy,
                'type' => $type,
            ];
            
            $orders = $this->orderService->getAll($page, $perPage, $filters);
            return apiResponse(true, $orders, "Orders retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, $ex->getCode() ?? 500);
        }
    }

    public function show($id)
    {
        try {
            $order = $this->orderService->findById($id);
            return apiResponse(true, $order, "Order retrieved successfully");
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

    public function getSellerInfo()
    {
        try {
            $sellerInfo = $this->orderService->getSellerInfo();
            return apiResponse(true, $sellerInfo, "Seller info retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function getSellerOrders()
    {
        try {
            $sellerOrders = $this->orderService->getSellerOrders();
            return apiResponse(true, $sellerOrders, "Seller orders retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function getSellerOrderById($id)
    {
        try {
            $sellerOrder = $this->orderService->getSellerOrderById($id);
            return apiResponse(true, $sellerOrder, "Seller order retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function getAllOrderStatuses()
    {
        try {
            $statuses = $this->orderService->getAllOrderStatuses();
            return apiResponse(true, $statuses, "Order statuses retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function addStatusToSellerOrder(Request $request, $sellerOrderId)
    {
        try {
            $validation = $this->validationService->validate($request, [
                'status_id' => 'required|integer|exists:order_statuses,id',
            ]);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $sellerOrder = $this->orderService->addStatusToSellerOrder($sellerOrderId, $request->status_id);

            return apiResponse(true, $sellerOrder, "Status added to seller order successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, $ex->getCode() ?? 500);
        }
    }
}
