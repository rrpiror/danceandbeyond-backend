<?php

namespace App\Console\Commands;

use App\Repositories\SellerOrderRepository;
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

    public function __construct(StripeService $stripeService, SellerOrderRepository $sellerOrderRepository, PayoutTransactionRepository $payoutTransactionRepository, TransactionRepository $transactionRepository)
    {
        parent::__construct();

        $this->stripeService = $stripeService;
        $this->sellerOrderRepository = $sellerOrderRepository;
        $this->payoutTransactionRepository = $payoutTransactionRepository;
        $this->transactionRepository = $transactionRepository;
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
            $amount = $order->amount * 100;
            $commission = $amount * env('PLATFORM_FEE');
            $finalAmount = $amount + $commission;

            $transfer = Transfer::create([
                'amount' => $finalAmount,
                'currency' => env('CASHIER_CURRENCY', 'gbp'),
                'destination' => $order->seller->seller->seller_stripe_id,
                'description' => "Payout for order #{$order->id}",
            ]);

            $order->update(['transferred_at' => now()]);

            $transaction = $this->transactionRepository->create([
                'stripe_payment_id' => $transfer->id,
                'amount' => $finalAmount,
                'type' => 'payout',
            ]);

            $this->payoutTransactionRepository->create([
                'transaction_id' => $transaction->id,
                'seller_id' => $order->seller_id,
                'commission' => $commission / 100,
            ]);

            $this->info("Funds released for order #{$order->id} to seller #{$order->seller_id}");
        }

        $this->info('Fund release process completed.');
    }
}
