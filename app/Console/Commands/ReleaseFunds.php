<?php

namespace App\Console\Commands;

use App\Repositories\SellerOrderRepository;
use App\Services\OrderService;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Stripe\Transfer;
use App\Repositories\PayoutTransactionRepository;
use App\Repositories\TransactionRepository;

class ReleaseFunds extends Command
{
    protected StripeService $stripeService;
    protected SellerOrderRepository $sellerOrderRepository;
    protected PayoutTransactionRepository $payoutTransactionRepository;
    protected TransactionRepository $transactionRepository;
    protected OrderService $orderService;

    public function __construct(StripeService $stripeService, SellerOrderRepository $sellerOrderRepository, PayoutTransactionRepository $payoutTransactionRepository, TransactionRepository $transactionRepository, OrderService $orderService)
    {
        parent::__construct();

        $this->stripeService = $stripeService;
        $this->sellerOrderRepository = $sellerOrderRepository;
        $this->payoutTransactionRepository = $payoutTransactionRepository;
        $this->transactionRepository = $transactionRepository;
        $this->orderService = $orderService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'funds:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release funds to seller after 14-days hold period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = $this->sellerOrderRepository->findOldOrders();

        foreach ($orders as $order) {
            try {
                if (!$order->seller?->stripe_seller_id) {
                    $this->warn("Skipping seller order #{$order->id}: seller has not connected Stripe.");
                    continue;
                }

                $payout = $this->orderService->calculateSellerOrderPayout($order);

                if ($payout['payout_amount'] <= 0) {
                    $this->warn("Skipping seller order #{$order->id}: payout amount must be greater than zero.");
                    continue;
                }

                $transfer = Transfer::create([
                    'amount' => $payout['payout_amount'],
                    'currency' => env('CASHIER_CURRENCY', 'gbp'),
                    'destination' => $order->seller->stripe_seller_id,
                    'description' => "Payout for order #{$order->id}",
                    'metadata' => [
                        'seller_order_id' => $order->id,
                        'order_id' => $order->order_id,
                        'seller_id' => $order->seller_id,
                        'gross_amount' => $payout['gross_amount'],
                        'platform_fee' => $payout['platform_fee'],
                        'stripe_fee' => $payout['stripe_fee'],
                    ],
                ]);

                $order->update(['transferred_at' => now()]);

                $transaction = $this->transactionRepository->create([
                    'stripe_payment_id' => $transfer->id,
                    'amount' => $payout['payout_amount'] / 100,
                    'type' => 'payout',
                ]);

                $this->payoutTransactionRepository->create([
                    'transaction_id' => $transaction->id,
                    'seller_id' => $order->seller_id,
                    'commission' => $payout['platform_fee'] / 100,
                ]);

                $this->info("Funds released for order #{$order->id} to seller #{$order->seller_id}");
            } catch (\Throwable $exception) {
                $this->error("Skipping seller order #{$order->id}: {$exception->getMessage()}");
            }
        }

        $this->info('Fund release process completed.');
    }
}
