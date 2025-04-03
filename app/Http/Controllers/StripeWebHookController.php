<?php

namespace App\Http\Controllers;

use App\Services\StripeWebHookService;
use Illuminate\Http\Request;
use Exception;

class StripeWebHookController extends Controller
{
    protected $stripeWebHookService;

    public function __construct(StripeWebHookService $stripeWebHookService)
    {
        $this->stripeWebHookService = $stripeWebHookService;
    }

    public function handleWebhook(Request $request)
    {
        try {
            $payload = $request->all();
            $this->stripeWebHookService->handleWebhook($payload);

            return apiResponse(true, null, "Webhook received");
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, $ex->getCode() ?? 500);
        }
    }
}
