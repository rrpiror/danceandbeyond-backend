<?php

namespace App\Http\Controllers;

use Exception;

class MarketplaceFeeController extends Controller
{
    public function index()
    {
        try {
            $platformFeeRate = (float) env('PLATFORM_FEE', 0);
            $stripeEstimatedRate = (float) env('STRIPE_ESTIMATED_FEE_RATE', 0.015);
            $stripeEstimatedFixedFee = (float) env('STRIPE_ESTIMATED_FIXED_FEE', 0.20);

            return apiResponse(true, [
                'platform_fee_rate' => $platformFeeRate,
                'stripe_estimated_fee_rate' => $stripeEstimatedRate,
                'stripe_estimated_fixed_fee' => $stripeEstimatedFixedFee,
                'currency' => 'GBP',
            ]);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
