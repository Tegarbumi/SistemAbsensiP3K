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
      Schema::create('leave_requests', function (Blueprint $table) {
    $table->id();

    $table->foreignId('employee_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->enum('jenis', [
        'izin',
        'sakit',
        'cuti',
        'dinas'
    ]);

    $table->date('tanggal_mulai');
    $table->date('tanggal_selesai');

    $table->text('alasan');

    $table->string('dokumen')->nullable();

    $table->enum('status', [
        'menunggu',
        'disetujui',
        'ditolak'
    ])->default('menunggu');

    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('approved_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
