<?php

namespace App\Services;

use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function initStripe()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function createStripePaymentIntent(array $data)
    {
        $this->initStripe();

        $amount = $data['amount'] * 100;
        $commission = $amount * env('PLATFORM_FEE');

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'application_fee_amount' => $commission,
            'transfer_data' => [
                'destination' => $data['seller_id'],
            ],
        ]);

        return $paymentIntent;
    }
}
