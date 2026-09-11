<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // clinic_visits indexes
        $this->addIndexIfNotExists('clinic_visits', 'visit_date', ['visit_date']);
        $this->addIndexIfNotExists('clinic_visits', 'created_at', ['created_at']);
        $this->addIndexIfNotExists('clinic_visits', 'visit_date_patient_id', ['visit_date', 'patient_id']);
        $this->addIndexIfNotExists('clinic_visits', 'created_at_patient_id', ['created_at', 'patient_id']);
        $this->addIndexIfNotExists('clinic_visits', 'visit_type', ['visit_type']);
        $this->addIndexIfNotExists('clinic_visits', 'patient_id_visit_date', ['patient_id', 'visit_date']);

        // appointments indexes
        $this->addIndexIfNotExists('appointments', 'appointment_date', ['appointment_date']);
        $this->addIndexIfNotExists('appointments', 'status', ['status']);
        $this->addIndexIfNotExists('appointments', 'appointment_date_status', ['appointment_date', 'status']);
        $this->addIndexIfNotExists('appointments', 'patient_id_appointment_date', ['patient_id', 'appointment_date']);

        // medicines indexes
        $this->addIndexIfNotExists('medicines', 'status', ['status']);
        $this->addIndexIfNotExists('medicines', 'expiration_date', ['expiration_date']);
        $this->addIndexIfNotExists('medicines', 'quantity_minimum_stock', ['quantity', 'minimum_stock']);
        $this->addIndexIfNotExists('medicines', 'status_expiration_date', ['status', 'expiration_date']);

        // patients indexes (category and email already exist)
        $this->addIndexIfNotExists('patients', 'name', ['name']);

        // users indexes
        $this->addIndexIfNotExists('users', 'approval_status', ['approval_status']);
        $this->addIndexIfNotExists('users', 'role', ['role']);

        // form_submissions indexes (form_type already indexed)
        if (Schema::hasTable('form_submissions')) {
            $this->addIndexIfNotExists('form_submissions', 'created_at', ['created_at']);
            $this->addIndexIfNotExists('form_submissions', 'submitted_at', ['submitted_at']);
        }
    }

    public function down(): void
    {
        $this->dropIndexIfExists('clinic_visits', 'visit_date');
        $this->dropIndexIfExists('clinic_visits', 'created_at');
        $this->dropIndexIfExists('clinic_visits', 'visit_date_patient_id');
        $this->dropIndexIfExists('clinic_visits', 'created_at_patient_id');
        $this->dropIndexIfExists('clinic_visits', 'visit_type');
        $this->dropIndexIfExists('clinic_visits', 'patient_id_visit_date');

        $this->dropIndexIfExists('appointments', 'appointment_date');
        $this->dropIndexIfExists('appointments', 'status');
        $this->dropIndexIfExists('appointments', 'appointment_date_status');
        $this->dropIndexIfExists('appointments', 'patient_id_appointment_date');

        $this->dropIndexIfExists('medicines', 'status');
        $this->dropIndexIfExists('medicines', 'expiration_date');
        $this->dropIndexIfExists('medicines', 'quantity_minimum_stock');
        $this->dropIndexIfExists('medicines', 'status_expiration_date');

        $this->dropIndexIfExists('patients', 'name');

        $this->dropIndexIfExists('users', 'approval_status');
        $this->dropIndexIfExists('users', 'role');

        if (Schema::hasTable('form_submissions')) {
            $this->dropIndexIfExists('form_submissions', 'created_at');
            $this->dropIndexIfExists('form_submissions', 'submitted_at');
        }
    }

    private function addIndexIfNotExists(string $table, string $name, array $columns): void
    {
        $indexName = $table . '_' . $name . '_index';
        
        // Check if index already exists
        $exists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
            AND table_name = ? 
            AND index_name = ?
        ", [$table, $indexName]);

        if (($exists[0]->count ?? 0) === 0) {
            Schema::table($table, function (Blueprint $table) use ($name, $columns) {
                $table->index($columns, $name);
            });
        }
    }

    private function dropIndexIfExists(string $table, string $name): void
    {
        $indexName = $table . '_' . $name . '_index';
        
        $exists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
            AND table_name = ? 
            AND index_name = ?
        ", [$table, $indexName]);

        if (($exists[0]->count ?? 0) > 0) {
            Schema::table($table, function (Blueprint $table) use ($name) {
                $table->dropIndex($name);
            });
        }
    }
};