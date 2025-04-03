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
            ['name' => 'Cancelled'],
            ['name' => 'Disput'],
            ['name' => 'Disput Resolved'],
        ];

        foreach ($statuses as $status) {
            OrderStatus::create($status);
        }
    }
}
