<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_code', 50)->nullable();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('maiden_name')->nullable();
            $table->string('sex');
            $table->string('birthday')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('civil_status')->nullable();
            $table->text('residential_address')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('course')->nullable();
            $table->string('year_section')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('spouse_name')->nullable();
            $table->json('past_medical_history')->nullable();
            $table->json('past_surgical_history')->nullable();
            $table->json('family_history')->nullable();
            $table->string('signature_name')->nullable();
            $table->string('signature_date')->nullable();
            $table->string('healthcare_provider_name')->nullable();
            $table->timestamps();

            $table->index('student_code');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_health_records');
    }
};
