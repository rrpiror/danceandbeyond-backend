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
        Schema::create('order_item_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('size_id');
            $table->integer('quantity');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_sizes');
    }
};
