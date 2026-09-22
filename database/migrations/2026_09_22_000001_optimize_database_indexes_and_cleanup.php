<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Bersihkan tabel flights sisa yang tidak digunakan
        Schema::dropIfExists('flights');

        // 2. Tambahkan indeks performa pada tabel attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->index('tanggal', 'attendances_tanggal_index');
            $table->index('status', 'attendances_status_index');
            if (Schema::hasColumn('attendances', 'unit_id')) {
                $table->index('unit_id', 'attendances_unit_id_index');
            }
            $table->index(['tanggal', 'status'], 'attendances_tanggal_status_index');
        });

        // 3. Tambahkan indeks performa pada tabel leave_requests
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->index('status', 'leave_requests_status_index');
            $table->index(['tanggal_mulai', 'tanggal_selesai'], 'leave_requests_periode_index');
        });

        // 4. Tambahkan indeks performa pada tabel audit_logs
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('action', 'audit_logs_action_index');
            $table->index(['model_type', 'model_id'], 'audit_logs_model_index');
            $table->index('created_at', 'audit_logs_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('audit_logs_action_index');
            $table->dropIndex('audit_logs_model_index');
            $table->dropIndex('audit_logs_created_at_index');
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropIndex('leave_requests_status_index');
            $table->dropIndex('leave_requests_periode_index');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_tanggal_index');
            $table->dropIndex('attendances_status_index');
            if (Schema::hasColumn('attendances', 'unit_id')) {
                $table->dropIndex('attendances_unit_id_index');
            }
            $table->dropIndex('attendances_tanggal_status_index');
        });
    }
};
