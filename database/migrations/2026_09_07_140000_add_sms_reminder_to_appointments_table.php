<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('sms_reminder')->default(false)->after('notes');
            $table->text('sms_message')->nullable()->after('sms_reminder');
            $table->string('sms_status')->nullable()->after('sms_message'); // pending, sent, failed
            $table->timestamp('sms_sent_at')->nullable()->after('sms_status');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['sms_reminder', 'sms_message', 'sms_status', 'sms_sent_at']);
        });
    }
};
