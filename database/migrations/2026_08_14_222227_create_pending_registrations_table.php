<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pending_registrations')) {
            Schema::create('pending_registrations', function (Blueprint $table) {
                $table->id();
                
                // Registration info
                $table->string('name');
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('phone', 20);
                $table->enum('role', ['student', 'faculty', 'staff', 'clinic_staff']);
                $table->string('password'); // Hashed
                
                // OTP
                $table->string('otp', 6)->nullable();
                $table->timestamp('otp_expires_at')->nullable();
                $table->boolean('otp_verified')->default(false);
                
                // Expiration
                $table->timestamp('expires_at')->nullable();
                
                // Timestamps
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
                
                // Indexes
                $table->index('email');
                $table->index('username');
                $table->index('otp_verified');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');
    }
};