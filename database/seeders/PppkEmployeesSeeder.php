<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PppkEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil unit kerja DISARPUS dan PERPUSDA
        $unitDisarpus = Unit::where('kode', 'DISARPUS-SBG')->first() ?? Unit::first();
        $unitPerpusda = Unit::where('kode', 'PERPUS-DAERAH')->first() 
            ?? Unit::where('kode', 'PERPUSDA-SBG')->first() 
            ?? $unitDisarpus;

        $employeesData = [
            [
                'nama' => 'NENG INTAN WIDIAWATI WAT, S.I.Pust',
                'nip' => '198608242023212018',
                'email' => 'nengintan@subang.go.id',
                'jabatan' => 'Pustakawan Ahli Pertama',
                'unit_id' => $unitPerpusda->id,
            ],
            [
                'nama' => 'SISKAWATI, S.I.Pust',
                'nip' => '199510112023212028',
                'email' => 'siskawati@subang.go.id',
                'jabatan' => 'Pustakawan Ahli Pertama',
                'unit_id' => $unitPerpusda->id,
            ],
            [
                'nama' => 'NANANG INDRAYANA, S.M.',
                'nip' => '197905042008011010',
                'email' => 'nanang.indrayana@subang.go.id',
                'jabatan' => 'Penelaah Teknis Kebijakan',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'RAIGINA AULIA, S.S.I.',
                'nip' => '199610032025042008',
                'email' => 'raigina.aulia@subang.go.id',
                'jabatan' => 'Pustakawan Ahli Pertama',
                'unit_id' => $unitPerpusda->id,
            ],
            [
                'nama' => 'DEDEN ZAHIR NURKOYYIM, S.E.',
                'nip' => '196812212025211009',
                'email' => 'deden.zahir@subang.go.id',
                'jabatan' => 'Arsiparis Mahir',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'BUDHIANTO',
                'nip' => '197310062009011001',
                'email' => 'budhianto@subang.go.id',
                'jabatan' => 'Pengolah Data dan Informasi',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'AGUS',
                'nip' => '198609192025211002',
                'email' => 'agus@subang.go.id',
                'jabatan' => 'Pengadministrasi Perkantoran',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'DIANA RESTARI UTAMI, S.Pd',
                'nip' => '199408102025212089',
                'email' => 'diana.restari@subang.go.id',
                'jabatan' => 'Penata Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'DHIMAS HANANTO DWIUTOMO',
                'nip' => '198405222025211052',
                'email' => 'dhimas.hananto@subang.go.id',
                'jabatan' => 'Operator Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'ASEP SAEPULOH',
                'nip' => '198312092025211050',
                'email' => 'asep.saepuloh@subang.go.id',
                'jabatan' => 'Operator Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'MUHAMAD FAHRI FAHMI HUSNI, S.Kom',
                'nip' => '199804022025211061',
                'email' => 'muhamad.fahri@subang.go.id',
                'jabatan' => 'Penata Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'ACU SAMSUDIN, S.Pd.I',
                'nip' => '198704172025211082',
                'email' => 'acu.samsudin@subang.go.id',
                'jabatan' => 'Penata Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'AGUS AZIS',
                'nip' => '197908102025211086',
                'email' => 'agus.azis@subang.go.id',
                'jabatan' => 'Operator Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'WILAWATI',
                'nip' => '198911162025212082',
                'email' => 'wilawati@subang.go.id',
                'jabatan' => 'Operator Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'EUIS HOTIMAH FARIDA',
                'nip' => '197610242025212013',
                'email' => 'euis.hotimah@subang.go.id',
                'jabatan' => 'Operator Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
            [
                'nama' => 'ELAWATI',
                'nip' => '198105012025212038',
                'email' => 'elawati@subang.go.id',
                'jabatan' => 'Operator Layanan Operasional',
                'unit_id' => $unitDisarpus->id,
            ],
        ];

        foreach ($employeesData as $data) {
            // 1. Buat atau perbarui akun User
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['nama'],
                    'password' => Hash::make('password'),
                    'role' => 'pppk',
                    'email_verified_at' => now(),
                ]
            );

            // 2. Buat atau perbarui data Employee
            Employee::updateOrCreate(
                ['nip' => $data['nip']],
                [
                    'user_id' => $user->id,
                    'unit_id' => $data['unit_id'],
                    'nama' => $data['nama'],
                    'jabatan' => $data['jabatan'],
                    'status' => true,
                ]
            );
        }
    }
}

