<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pending_registrations', function (Blueprint $table) {
            // ✅ ADD USERNAME COLUMN IF NOT EXISTS
            if (!Schema::hasColumn('pending_registrations', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pending_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('pending_registrations', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
};