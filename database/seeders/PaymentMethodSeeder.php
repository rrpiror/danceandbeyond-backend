<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Stripe',
                'description' => 'Pay with Stripe',
            ],
            [
                'name' => 'Debit Card',
                'description' => 'Pay directly from your bank account',
            ],
            [
                'name' => 'PayPal',
                'description' => 'Pay securely with your PayPal account',
            ],
            [
                'name' => 'Apple Pay',
                'description' => 'Quick and secure payment with Apple devices',
            ],
            [
                'name' => 'Google Pay',
                'description' => 'Fast checkout with Google Pay',
            ],
            [
                'name' => 'Bank Transfer',
                'description' => 'Direct bank transfer to seller account',
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
} 