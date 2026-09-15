<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@subang.go.id'],
            [
                'name' => 'Administrator Disarpus',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Unit Kerja
        $unit = Unit::firstOrCreate(
            ['kode' => 'DISARPUS-SBG'],
            [
                'nama' => 'Dinas Kearsipan dan Perpustakaan Kabupaten Subang',
                'alamat' => 'Jl. MT Haryono No. 9, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211',
                'status' => true,
            ]
        );

        // 3. Lokasi Kantor Disarpus Subang
        $office = OfficeLocation::firstOrCreate(
            ['nama' => 'Kantor Dinas Kearsipan dan Perpustakaan'],
            [
                'unit_id' => $unit->id,
                'alamat' => 'Jl. MT Haryono No. 9, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211',
                'latitude' => -6.5683000,
                'longitude' => 107.7634000,
                'radius_meter' => 100,
                'status' => true,
            ]
        );

        // 4. Jadwal Kerja
        $schedule = WorkSchedule::firstOrCreate(
            ['nama' => 'Jadwal Kerja Reguler PPPK'],
            [
                'jam_masuk' => '07:30:00',
                'jam_pulang' => '16:00:00',
                'toleransi_terlambat' => 15,
                'senin' => true,
                'selasa' => true,
                'rabu' => true,
                'kamis' => true,
                'jumat' => true,
                'sabtu' => false,
                'minggu' => false,
            ]
        );

        // 5. Akun PPPK
        $userPppk = User::firstOrCreate(
            ['email' => 'pppk@subang.go.id'],
            [
                'name' => 'Ahmad Hidayat, S.IP',
                'password' => Hash::make('password'),
                'role' => 'pppk',
                'email_verified_at' => now(),
            ]
        );

        // 6. Data Pegawai PPPK
        Employee::firstOrCreate(
            ['nip' => '199008152023211001'],
            [
                'user_id' => $userPppk->id,
                'unit_id' => $unit->id,
                'nomor_pppk' => 'PPPK-2023-0012',
                'nama' => 'Ahmad Hidayat, S.IP',
                'jabatan' => 'Arsiparis Ahli Pertama',
                'status' => true,
            ]
        );
    }
}
