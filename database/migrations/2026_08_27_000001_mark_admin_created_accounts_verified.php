<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('approval_status', 'approved')
            ->where('is_active', true)
            ->where('otp_verified', false)
            ->update([
                'otp_verified' => true,
                'must_change_password' => false,
            ]);
    }

    public function down(): void
    {
        // Existing account verification state is intentionally not reversed.
    }
};