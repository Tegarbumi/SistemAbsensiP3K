<?php

use App\Models\Unit;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Unit::firstOrCreate(
            ['kode' => 'DINAS-LAPANGAN'],
            [
                'nama' => 'Unit Pelaksana Teknis Dinas Lapangan',
                'alamat' => 'Penugasan Lapangan / Mobile (Sistem GPS Sharelok)',
                'status' => true,
                'is_dinas_lapangan' => true,
            ]
        );

        // Pastikan is_dinas_lapangan bernilai true jika unit sudah ada
        Unit::where('kode', 'DINAS-LAPANGAN')
            ->orWhere('nama', 'like', '%Dinas Lapangan%')
            ->update([
                'nama' => 'Unit Pelaksana Teknis Dinas Lapangan',
                'is_dinas_lapangan' => true,
                'status' => true,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tetap dipertahankan agar tidak merusak data relasi
    }
};
