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
        Schema::create('hiring_unavailability_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hiring_detail_id');
            $table->date('date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('hiring_detail_id')->references('id')->on('hiring_details')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hiring_unavailability_days');
    }
};
