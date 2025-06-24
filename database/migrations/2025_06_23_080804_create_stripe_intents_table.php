<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stripe_intents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_intent_id')->unique();
            $table->string('client_secret');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('gbp');
            $table->string('status')->default('requires_payment_method'); // Stripe payment intent status
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['order_id', 'status']);
            $table->index('payment_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_intents');
    }
};
