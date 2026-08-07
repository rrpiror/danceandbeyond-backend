<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['Returned in Transit', 'Completed', 'Overdue'] as $status) {
            DB::table('order_statuses')->updateOrInsert(
                ['name' => $status],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('order_statuses')
            ->whereIn('name', ['Returned in Transit', 'Completed', 'Overdue'])
            ->delete();
    }
};
