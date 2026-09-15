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
        Schema::create('attendances', function (Blueprint $table) {
    $table->id();

    $table->foreignId('employee_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->date('tanggal');

    $table->time('jam_masuk')->nullable();
    $table->time('jam_pulang')->nullable();

    $table->decimal('latitude_masuk', 10, 7)->nullable();
    $table->decimal('longitude_masuk', 10, 7)->nullable();
    $table->decimal('accuracy_masuk', 10, 2)->nullable();

    $table->decimal('latitude_pulang', 10, 7)->nullable();
    $table->decimal('longitude_pulang', 10, 7)->nullable();
    $table->decimal('accuracy_pulang', 10, 2)->nullable();

    $table->string('selfie_masuk')->nullable();
    $table->string('selfie_pulang')->nullable();

    $table->enum('status', [
        'hadir',
        'terlambat',
        'izin',
        'sakit',
        'dinas',
        'cuti',
        'alpha'
    ])->default('hadir');

    $table->text('keterangan')->nullable();

    $table->string('ip_address')->nullable();
    $table->text('user_agent')->nullable();

    $table->timestamps();

    $table->unique([
        'employee_id',
        'tanggal'
    ]);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
