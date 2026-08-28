<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('patients')) {
            Schema::create('patients', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique()->nullable();
                $table->string('phone', 20)->nullable();
                $table->string('year_section')->nullable();
                $table->integer('age')->nullable();
                $table->string('blood_type')->nullable();
                $table->text('address')->nullable();
                $table->enum('category', ['student', 'faculty', 'staff'])->default('student');
                $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
                $table->timestamps();
                $table->index('email');
                $table->index('category');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};