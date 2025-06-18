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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->text('password');
            $table->boolean('email_verified')->default(true);
            $table->boolean('remember_me')->default(false);
            $table->dateTime('remember_me_at')->nullable();
            $table->rememberToken();
            $table->text('reset_password_token')->nullable();
            $table->dateTime('reset_password_token_at')->nullable();
            $table->enum('type', ['individual', 'school']);
            $table->enum('status', ['active', 'blocked']);
            $table->string('stripe_seller_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
