<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

/**
 * PaymentService handles all payment-related operations including Stripe integration
 */
class PaymentService
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Create a Stripe Payment Intent for order payment (for Flutter Stripe integration)
     * 
     * @param array $data Order data including id and other details
     * @param array $items Array of order items with product details
     * @return array Payment Intent details including client secret
     */
    public function createStripePaymentIntent(array $data, $items)
    {
        $user = Auth::user();

        // Calculate total amount in pence
        $totalAmount = 0;
        foreach ($items as $item) {
            $totalAmount += (int) round($item['price'] * $item['quantity']);
        }

        // Create Payment Intent
        $paymentIntent = PaymentIntent::create([
            'amount' => $totalAmount,
            'currency' => env('CASHIER_CURRENCY', 'gbp'),
            'customer' => $user->stripe_customer_id,
            'metadata' => [
                'order_id' => $data['id'],
                'user_id' => $user->id,
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return [
            'client_secret' => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $totalAmount,
            'currency' => $paymentIntent->currency,
        ];
    }

    /**
     * Create a Stripe Checkout Session for web-based payments (fallback)
     * 
     * @param array $data Order data including id and other details
     * @param array $items Array of order items with product details
     * @return string Stripe Checkout Session URL
     */
    public function createStripeCheckoutSession(array $data, $items)
    {
        $user = Auth::user();

        // Prepare line items for Stripe
        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => env('CASHIER_CURRENCY', 'gbp'),
                    'product_data' => [
                        'name' => $item['name'],
                        'metadata' => [
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity']
                        ]
                    ],
                    'unit_amount' => $item['price'],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // Create Stripe Checkout Session with enhanced metadata
        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'customer' => $user->stripe_customer_id,
            'line_items' => $lineItems,
            'success_url' => "https://www.example.com",
            'cancel_url' => "https://www.example.com",
            'payment_intent_data' => [
                'metadata' => [
                    'order_id' => $data['id'],
                    'user_id' => $user->id,
                ]
            ],
        ]);

        return $session->url;
    }

    public function createFeatureItemPaymentIntent(array $data)
    {
        $user = Auth::user();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'customer' => $user->stripe_customer_id,
            'line_items' => [[
                'price_data' => [
                    'currency' => env('CASHIER_CURRENCY', 'gbp'),
                    'product_data' => [
                        'name' => $data['name'],
                        'metadata' => [
                            'product_id' => $data['id'],
                        ]
                    ],
                    'unit_amount' => env('FEATURED_ITEM_PRICE', 1000),
                ],
                'quantity' => 1,
            ]],
            'success_url' => "https://www.example.com",
            'cancel_url' => "https://www.example.com",
            'payment_intent_data' => [
                'metadata' => [
                    'product_id' => $data['id'],
                    'user_id' => $user->id,
                ]
            ],
        ]);

        return $session->url;
    }
}
