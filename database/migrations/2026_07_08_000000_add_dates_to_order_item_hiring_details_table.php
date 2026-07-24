<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_item_hiring_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_item_hiring_details', 'start_date')) {
                $table->date('start_date')->nullable()->after('hiring_days');
            }

            if (!Schema::hasColumn('order_item_hiring_details', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_item_hiring_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_item_hiring_details', 'end_date')) {
                $table->dropColumn('end_date');
            }

            if (Schema::hasColumn('order_item_hiring_details', 'start_date')) {
                $table->dropColumn('start_date');
            }
        });
    }
};
