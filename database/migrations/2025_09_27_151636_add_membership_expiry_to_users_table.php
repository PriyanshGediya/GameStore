<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if the column does NOT exist before adding it
        if (!Schema::hasColumn('users', 'membership_expiry')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('membership_expiry')->nullable()->after('remember_token');
            });
        }
    }

    public function down(): void
    {
        // Check if the column EXISTS before trying to drop it
        if (Schema::hasColumn('users', 'membership_expiry')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('membership_expiry');
            });
        }
    }
};