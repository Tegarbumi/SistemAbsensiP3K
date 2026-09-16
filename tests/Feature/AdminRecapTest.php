<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRecapTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $pppkUser;
    protected Unit $unit;
    protected Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@subang.go.id',
        ]);

        $this->unit = Unit::create([
            'kode' => 'ARSIP-01',
            'nama' => 'Bidang Kearsipan',
            'status' => true,
        ]);

        $this->pppkUser = User::factory()->create([
            'role' => 'pppk',
            'email' => 'pppk@subang.go.id',
        ]);

        $this->employee = Employee::create([
            'user_id' => $this->pppkUser->id,
            'unit_id' => $this->unit->id,
            'nip' => '199001012024211001',
            'nomor_pppk' => 'PPPK-2024-001',
            'nama' => 'Ahmad Subang',
            'jabatan' => 'Arsiparis Ahli Pertama',
            'status' => true,
        ]);
    }

    public function test_guest_tidak_dapat_mengakses_halaman_rekap(): void
    {
        $response = $this->get('/admin/rekap');
        $response->assertRedirect('/login');
    }

    public function test_pppk_tidak_dapat_mengakses_rekap_admin(): void
    {
        $response = $this->actingAs($this->pppkUser)->get('/admin/rekap');
        $response->assertStatus(403);
    }

    public function test_admin_dapat_melihat_halaman_rekap_dengan_data_hadir(): void
    {
        // Buat absensi hadir pada bulan ini
        Attendance::create([
            'employee_id' => $this->employee->id,
            'tanggal' => Carbon::now()->startOfMonth()->toDateString(),
            'jam_masuk' => '07:30:00',
            'status' => 'hadir',
            'latitude_masuk' => -6.5683,
            'longitude_masuk' => 107.7634,
            'distance_masuk' => 20,
            'accuracy_masuk' => 5,
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/rekap');
        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Absensi PPPK');
        $response->assertSee('Ahmad Subang');
        $response->assertSee('Bidang Kearsipan');
    }

    public function test_admin_dapat_memfilter_rekap_berdasarkan_unit_dan_bulan(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/rekap?' . http_build_query([
            'month' => now()->month,
            'year' => now()->year,
            'unit_id' => $this->unit->id,
            'employee_id' => $this->employee->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Subang');
    }
}
