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
        if (!Schema::hasColumn('user_schools', 'name')) {
            Schema::table('user_schools', function (Blueprint $table) {
                $table->string('name')->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('user_schools', 'name')) {
            Schema::table('user_schools', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};
