<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\OfficeLocation;
use App\Models\Unit;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $pppkUser;
    protected Employee $employee;
    protected OfficeLocation $officeLocation;

    protected function setUp(): void
    {
        parent::setUp();

        $unit = Unit::create([
            'kode' => 'ARSIP-01',
            'nama' => 'Bidang Kearsipan',
            'status' => true,
        ]);

        $this->pppkUser = User::factory()->create([
            'role' => 'pppk',
        ]);

        $this->employee = Employee::create([
            'user_id' => $this->pppkUser->id,
            'unit_id' => $unit->id,
            'nip' => '199001012024211001',
            'nomor_pppk' => 'PPPK-2024-001',
            'nama' => 'Ahmad Subang',
            'jabatan' => 'Arsiparis Ahli Pertama',
            'status' => true,
        ]);

        $this->officeLocation = OfficeLocation::create([
            'unit_id' => $unit->id,
            'nama' => 'Kantor Disarpus Subang',
            'alamat' => 'Jl. Mayjen Sutoyo No. 1, Subang',
            'latitude' => -6.5683,
            'longitude' => 107.7634,
            'radius_meter' => 100,
            'status' => true,
        ]);
    }

    public function test_check_in_menolak_akurasi_gps_abnormal(): void
    {
        $file = UploadedFile::fake()->image('selfie.jpg');

        $response = $this->actingAs($this->pppkUser)->postJson('/absensi/check-in', [
            'latitude' => -6.5683,
            'longitude' => 107.7634,
            'accuracy' => 5500, // Akurasi abnormal di luar toleransi
            'selfie' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['accuracy']);
    }

    public function test_check_in_berhasil_mencatat_audit_log(): void
    {
        $file = UploadedFile::fake()->image('selfie.jpg');

        $response = $this->actingAs($this->pppkUser)->postJson('/absensi/check-in', [
            'latitude' => -6.5683,
            'longitude' => 107.7634,
            'accuracy' => 10,
            'selfie' => $file,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->pppkUser->id,
            'action' => 'check_in',
        ]);
    }

    public function test_audit_log_record_helper_menyimpan_data_dengan_benar(): void
    {
        $log = AuditLog::record(
            $this->pppkUser->id,
            'test_action',
            'TestModel',
            123,
            ['keterangan' => 'Uji coba audit log'],
            '127.0.0.1',
            'PHPUnit Agent'
        );

        $this->assertNotNull($log->id);
        $this->assertEquals('test_action', $log->action);
        $this->assertEquals('127.0.0.1', $log->ip_address);
        $this->assertEquals('PHPUnit Agent', $log->user_agent);
    }
}
