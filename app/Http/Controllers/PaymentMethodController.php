<?php

namespace App\Http\Controllers;

use App\Services\PaymentMethodService;
use Exception;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    protected PaymentMethodService $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function index()
    {
        try {
            $methods = $this->paymentMethodService->getAll();
            return apiResponse(true, $methods);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
