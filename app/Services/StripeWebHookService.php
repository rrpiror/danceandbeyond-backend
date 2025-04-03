<?php

namespace App\Services;

use App\Repositories\SellerOrderRepository;
use App\Repositories\OrderStatusRepository;
use Exception;
use Stripe\Event;
use UnexpectedValueException;
use Illuminate\Support\Facades\Log;
use App\Repositories\TransactionRepository;
use App\Repositories\OrderTransactionRepository;

/**
 * StripeWebHookService handles all Stripe webhook events
 */
class StripeWebHookService
{
    protected StripeService $stripeService;
    protected SellerOrderRepository $sellerOrderRepository;
    protected OrderStatusRepository $orderStatusRepository;
    protected TransactionRepository $transactionRepository;
    protected OrderTransactionRepository $orderTransactionRepository;

    public function __construct(StripeService $stripeService, SellerOrderRepository $sellerOrderRepository, OrderStatusRepository $orderStatusRepository, TransactionRepository $transactionRepository, OrderTransactionRepository $orderTransactionRepository)
    {
        $this->stripeService = $stripeService;
        $this->sellerOrderRepository = $sellerOrderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->transactionRepository = $transactionRepository;
        $this->orderTransactionRepository = $orderTransactionRepository;
    }

    public function handleWebhook($payload)
    {
        $event = null;

        try {
            $event = Event::constructFrom($payload);
        } catch (UnexpectedValueException $e) {
            throw new Exception('Invalid payload', 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                return $this->handlePaymentIntentSucceeded($event->data->object);

            case 'payment_intent.payment_failed':
                return $this->handlePaymentIntentFailed($event->data->object);

            default:
                throw new Exception('Unhandled event type', 400);
        }
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Get the order ID from metadata
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if (!$orderId) {
            Log::error('Payment Intent succeeded but no order_id in metadata', [
                'payment_intent_id' => $paymentIntent->id,
                'metadata' => $paymentIntent->metadata
            ]);
            return false;
        }

        $transaction = $this->transactionRepository->create([
            'stripe_payment_id' => $paymentIntent->id,
            'type' => 'order',
            'amount' => $paymentIntent->amount / 100,
        ]);

        $this->orderTransactionRepository->create([
            'transaction_id' => $transaction->id,
            'order_id' => $orderId,
            'user_id' => $paymentIntent->metadata->user_id,
        ]);

        // Find seller order and update status to payment confirmed
        $sellerOrders = $this->sellerOrderRepository->findByOrderId($orderId);

        foreach ($sellerOrders as $sellerOrder) {
            $sellerOrder->statuses()->attach($this->orderStatusRepository->getStatusByName('Payment Confirmed')->id);
        }

        return true;
    }

    protected function handlePaymentIntentFailed($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if (!$orderId) {
            Log::error('Payment Intent failed but no order_id in metadata', [
                'payment_intent_id' => $paymentIntent->id,
                'metadata' => $paymentIntent->metadata
            ]);
            return false;
        }

        // Find seller order and update status to payment failed
        $sellerOrders = $this->sellerOrderRepository->findByOrderId($orderId);

        foreach ($sellerOrders as $sellerOrder) {
            $sellerOrder->statuses()->attach($this->orderStatusRepository->getStatusByName('Payment Failed')->id);
        }

        // Handle failed payment logic here
        return true;
    }
}
