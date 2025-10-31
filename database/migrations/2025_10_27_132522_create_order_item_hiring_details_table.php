<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\OrderItem;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_item_hiring_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrderItem::class)->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('hiring_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_hiring_details');
    }
};
