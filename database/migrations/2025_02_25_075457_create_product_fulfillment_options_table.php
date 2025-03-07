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
        Schema::create('product_fulfillment_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fulfillment_option_id');
            $table->unsignedBigInteger('product_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fulfillment_option_id')->references('id')->on('fulfillment_options')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_fulfillment_options');
    }
};
