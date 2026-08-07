<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('orders:mark-overdue', function () {
    $updated = app(\App\Services\OrderService::class)->markOverdueSellerOrders();

    $this->info("Marked {$updated} hire seller orders as overdue.");
})->purpose('Mark delivered hire orders overdue when the return period has passed');

Schedule::command('orders:mark-overdue')->daily();
