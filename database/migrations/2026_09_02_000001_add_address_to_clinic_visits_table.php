<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_visits', function (Blueprint $table) {
            if (!Schema::hasColumn('clinic_visits', 'address')) {
                $table->text('address')->nullable()->after('notes');
            }
        });

        DB::statement("
            UPDATE clinic_visits
            SET address = (
                SELECT p.address
                FROM patients p
                WHERE p.id = clinic_visits.patient_id
            )
            WHERE address IS NULL OR TRIM(address) = ''
        ");
    }

    public function down(): void
    {
        Schema::table('clinic_visits', function (Blueprint $table) {
            if (Schema::hasColumn('clinic_visits', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
