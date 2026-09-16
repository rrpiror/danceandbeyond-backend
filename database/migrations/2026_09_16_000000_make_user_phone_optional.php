<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('users')->whereNull('phone_number')->update(['phone_number' => '']);
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable(false)->change();
        });
    }
};
