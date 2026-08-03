<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hiring_details', function (Blueprint $table) {
            if (!Schema::hasColumn('hiring_details', 'deposit_amount')) {
                $table->decimal('deposit_amount', 10, 2)->default(0)->after('additional_fee_per_day');
            }
        });

        Schema::table('order_item_hiring_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_item_hiring_details', 'deposit_amount')) {
                $table->decimal('deposit_amount', 10, 2)->default(0)->after('end_date');
            }
            if (!Schema::hasColumn('order_item_hiring_details', 'deposit_status')) {
                $table->string('deposit_status')->default('held')->after('deposit_amount');
            }
            if (!Schema::hasColumn('order_item_hiring_details', 'deposit_resolved_at')) {
                $table->timestamp('deposit_resolved_at')->nullable()->after('deposit_status');
            }
            if (!Schema::hasColumn('order_item_hiring_details', 'deposit_dispute_reason')) {
                $table->text('deposit_dispute_reason')->nullable()->after('deposit_resolved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_item_hiring_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_item_hiring_details', 'deposit_dispute_reason')) {
                $table->dropColumn('deposit_dispute_reason');
            }
            if (Schema::hasColumn('order_item_hiring_details', 'deposit_resolved_at')) {
                $table->dropColumn('deposit_resolved_at');
            }
            if (Schema::hasColumn('order_item_hiring_details', 'deposit_status')) {
                $table->dropColumn('deposit_status');
            }
            if (Schema::hasColumn('order_item_hiring_details', 'deposit_amount')) {
                $table->dropColumn('deposit_amount');
            }
        });

        Schema::table('hiring_details', function (Blueprint $table) {
            if (Schema::hasColumn('hiring_details', 'deposit_amount')) {
                $table->dropColumn('deposit_amount');
            }
        });
    }
};
