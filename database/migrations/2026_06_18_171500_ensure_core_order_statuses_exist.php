<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['Pending', 'Payment Pending', 'Payment Confirmed', 'Delivered', 'Payment Failed'] as $status) {
            $exists = DB::table('order_statuses')->where('name', $status)->exists();

            if (!$exists) {
                DB::table('order_statuses')->insert([
                    'name' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
