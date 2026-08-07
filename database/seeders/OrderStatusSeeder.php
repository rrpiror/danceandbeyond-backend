<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending'],
            ['name' => 'Processing'],
            ['name' => 'Order Confirmed'],
            ['name' => 'Payment Pending'],
            ['name' => 'Payment Confirmed'],
            ['name' => 'Shipped'],
            ['name' => 'Delivered'],
            ['name' => 'Returned in Transit'],
            ['name' => 'Completed'],
            ['name' => 'Overdue'],
            ['name' => 'Cancelled'],
            ['name' => 'Dispute'],
            ['name' => 'Dispute Resolved'],
            ['name' => 'Payment Failed'],
        ];

        foreach ($statuses as $status) {
            OrderStatus::firstOrCreate(['name' => $status['name']], $status);
        }
    }
}
