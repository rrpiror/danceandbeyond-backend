<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected ValidationService $validationService;
    private const CREATE_RULES = [
        // 'payment_method_id' => 'required|integer',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.variant_id' => 'nullable|integer|exists:product_variants,id',
        'items.*.hiring_days' => 'nullable|integer|min:1',
        'items.*.start_date' => 'nullable|date',
        'items.*.end_date' => 'nullable|date',
        'billing_address_id' => 'nullable|required_without:billing_address|integer|exists:addresses,id',
        'shipping_address_id' => 'nullable|required_without:shipping_address|integer|exists:addresses,id',
        'billing_address' => 'nullable|required_without:billing_address_id|array',
        'billing_address.house_number' => 'required_with:billing_address|string|max:255',
        'billing_address.building_name' => 'nullable|string|max:255',
        'billing_address.street' => 'required_with:billing_address|string|max:255',
        'billing_address.town' => 'nullable|string|max:255',
        'billing_address.city' => 'required_with:billing_address|string|max:255',
        'billing_address.postcode' => 'required_with:billing_address|string|max:255',
        'shipping_address' => 'nullable|required_without:shipping_address_id|array',
        'shipping_address.house_number' => 'required_with:shipping_address|string|max:255',
        'shipping_address.building_name' => 'nullable|string|max:255',
        'shipping_address.street' => 'required_with:shipping_address|string|max:255',
        'shipping_address.town' => 'nullable|string|max:255',
        'shipping_address.city' => 'required_with:shipping_address|string|max:255',
        'shipping_address.postcode' => 'required_with:shipping_address|string|max:255',
        'save_billing_address' => 'nullable|boolean',
        'save_shipping_address' => 'nullable|boolean',
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
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
        }
    }

    public function show($id)
    {
        try {
            $order = $this->orderService->findById($id);
            return apiResponse(true, $order, "Order retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
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

            return successResponse($order, "Order placed");
        } catch (Exception $ex) {
            $errors = null;
            if ($ex instanceof ValidationException) {
                $errors = $ex->validator->errors();
            }
            return errorResponse($ex->getMessage(), $errors, $ex->getCode() ?? 1, $ex->getCode() ?? 400);
        }
    }

    public function getSellerInfo()
    {
        try {
            $sellerInfo = $this->orderService->getSellerInfo();
            return apiResponse(true, $sellerInfo, "Seller info retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
        }
    }

    public function getSellerOrders()
    {
        try {
            $sellerOrders = $this->orderService->getSellerOrders();
            return apiResponse(true, $sellerOrders, "Seller orders retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, 500);
        }
    }

    public function getSellerOrderById($id)
    {
        try {
            $sellerOrder = $this->orderService->getSellerOrderById($id);
            return apiResponse(true, $sellerOrder, "Seller order retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, 500);
        }
    }

    public function getAllOrderStatuses()
    {
        try {
            $statuses = $this->orderService->getAllOrderStatuses();
            return apiResponse(true, $statuses, "Order statuses retrieved successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
        }
    }

    public function syncStripePayment($id)
    {
        try {
            $order = $this->orderService->syncStripePayment($id);
            return apiResponse(true, $order, "Payment status synced successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() ?: 500);
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
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
        }
    }

    public function releaseSellerOrderFunds($sellerOrderId)
    {
        try {
            $sellerOrder = $this->orderService->releaseSellerOrderFunds($sellerOrderId);

            return apiResponse(true, $sellerOrder, "Funds released successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
        }
    }

    public function releaseSellerOrderDeposit($sellerOrderId)
    {
        try {
            $sellerOrder = $this->orderService->releaseSellerOrderDeposit($sellerOrderId);

            return apiResponse(true, $sellerOrder, "Deposit released successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
        }
    }

    public function retainSellerOrderDeposit($sellerOrderId)
    {
        try {
            $sellerOrder = $this->orderService->retainSellerOrderDeposit($sellerOrderId);

            return apiResponse(true, $sellerOrder, "Deposit retained successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
        }
    }

    public function disputeSellerOrderDeposit(Request $request, $sellerOrderId)
    {
        try {
            $validation = $this->validationService->validate($request, [
                'reason' => 'required|string|min:10',
            ]);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $sellerOrder = $this->orderService->disputeSellerOrderDeposit($sellerOrderId, $request->reason);

            return apiResponse(true, $sellerOrder, "Deposit disputed successfully");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), $ex->getMessage() ?? 'Something went wrong', $ex->getCode() ?? 1, $ex->getCode() || 500);
        }
    }
}
