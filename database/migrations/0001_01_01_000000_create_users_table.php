<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Basic info
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            
            // Authentication
            $table->string('password');
            $table->rememberToken();
            
            // Role & Status
            $table->enum('role', ['student', 'faculty', 'staff', 'clinic_nurse', 'clinic_staff']);
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'disabled'])->default('pending');
            $table->boolean('is_active')->default(false);
            
            // Registration tracking
            $table->boolean('otp_verified')->default(false);
            $table->boolean('is_bulk_created')->default(false);
            $table->boolean('must_change_password')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            
            // Clinic staff fields
            $table->string('clinic_name')->nullable();
            $table->string('clinic_phone', 20)->nullable();
            $table->string('clinic_address')->nullable();
            $table->string('clinic_hours')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('email');
            $table->index('username');
            $table->index('approval_status');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};