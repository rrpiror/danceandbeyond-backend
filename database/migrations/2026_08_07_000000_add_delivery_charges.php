<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'delivery_charge')) {
                $table->decimal('delivery_charge', 10, 2)->default(0)->after('price');
            }
        });

        Schema::table('seller_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_orders', 'delivery_charge')) {
                $table->decimal('delivery_charge', 10, 2)->default(0)->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seller_orders', function (Blueprint $table) {
            if (Schema::hasColumn('seller_orders', 'delivery_charge')) {
                $table->dropColumn('delivery_charge');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'delivery_charge')) {
                $table->dropColumn('delivery_charge');
            }
        });
    }
};
