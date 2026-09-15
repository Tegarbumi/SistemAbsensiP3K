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
        Schema::create('work_schedules', function (Blueprint $table) {
    $table->id();

    $table->string('nama');

    $table->time('jam_masuk');
    $table->time('jam_pulang');

    $table->integer('toleransi_terlambat')
        ->default(15);

    $table->boolean('senin')->default(true);
    $table->boolean('selasa')->default(true);
    $table->boolean('rabu')->default(true);
    $table->boolean('kamis')->default(true);
    $table->boolean('jumat')->default(true);
    $table->boolean('sabtu')->default(false);
    $table->boolean('minggu')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
