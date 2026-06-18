<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['colour_id']);
            $table->dropForeign(['size_id']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('colour_id')->nullable()->change();
            $table->unsignedBigInteger('size_id')->nullable()->change();

            $table->foreign('colour_id')->references('id')->on('product_colours')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('size_id')->references('id')->on('product_sizes')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['colour_id']);
            $table->dropForeign(['size_id']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('colour_id')->nullable(false)->change();
            $table->unsignedBigInteger('size_id')->nullable(false)->change();

            $table->foreign('colour_id')->references('id')->on('product_colours')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('size_id')->references('id')->on('product_sizes')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
};
