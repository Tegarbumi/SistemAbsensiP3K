<?php

use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    // Unit Dinas Lapangan
    $this->unitLapangan = Unit::create([
        'kode' => 'UNIT-LAP-TEST',
        'nama' => 'Unit Pelaksana Teknis Dinas Lapangan Test',
        'alamat' => 'Penugasan Lapangan / Mobile',
        'status' => true,
        'is_dinas_lapangan' => true,
    ]);

    // Jadwal Kerja
    $this->schedule = WorkSchedule::create([
        'nama' => 'Jadwal Kerja Reguler',
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
    ]);

    // User & Pegawai PPPK Lapangan
    $this->userPppk = User::create([
        'name' => 'Petugas Lapangan Test',
        'email' => 'lapangan.test@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->employee = Employee::create([
        'user_id' => $this->userPppk->id,
        'unit_id' => $this->unitLapangan->id,
        'nip' => '199501012023211099',
        'nomor_pppk' => 'PPPK-LAP-001',
        'nama' => 'Petugas Lapangan Test',
        'jabatan' => 'Petugas Lapangan',
        'status' => true,
    ]);

    // User Admin
    $this->userAdmin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin.lapangan@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
});

test('pppk unit dinas lapangan dapat mengakses halaman absensi tanpa lokasi kantor tetap', function () {
    $response = $this->actingAs($this->userPppk)->get(route('attendance.index'));
    $response->assertStatus(200);
    $response->assertSee('Mode Presensi Dinas Lapangan (Sistem Sharelok GPS)');
    $response->assertSee('Nama Tempat / Keterangan Penugasan Lapangan:');
    $response->assertDontSee('Lokasi kantor belum diatur. Silakan hubungi admin.');
});

test('pppk unit dinas lapangan dapat check-in di titik lokasi sharelok dan otomatis berstatus dinas', function () {
    $file = UploadedFile::fake()->image('selfie_lapangan.jpg');

    // Titik lokasi sembarang di lapangan (misal di Kecamatan Pagaden: -6.4950, 107.8200)
    $response = $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.4950,
        'longitude' => 107.8200,
        'accuracy' => 12,
        'selfie' => $file,
        'lokasi_lapangan' => 'Inspeksi Fisik Arsip Kantor Camat Pagaden',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'attendance' => [
            'status' => 'dinas',
        ],
    ]);

    $attendance = Attendance::where('employee_id', $this->employee->id)->first();
    expect($attendance)->not->toBeNull();
    expect($attendance->status)->toEqual('dinas');
    expect((float)$attendance->latitude_masuk)->toEqual(-6.4950);
    expect((float)$attendance->longitude_masuk)->toEqual(107.8200);
    expect($attendance->keterangan)->toContain('Dinas Lapangan (Sharelok)');
    expect($attendance->keterangan)->toContain('Kantor Camat Pagaden');

    // Memastikan tersimpan di attendance_locations
    $loc = AttendanceLocation::where('attendance_id', $attendance->id)->first();
    expect($loc)->not->toBeNull();
    expect((float)$loc->latitude)->toEqual(-6.4950);
});

test('pppk unit dinas lapangan dapat check-out di titik lokasi penugasan lapangan', function () {
    $file1 = UploadedFile::fake()->image('selfie_masuk.jpg');
    $file2 = UploadedFile::fake()->image('selfie_pulang.jpg');

    // Check-in di lokasi 1
    $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.4950,
        'longitude' => 107.8200,
        'accuracy' => 10,
        'selfie' => $file1,
        'lokasi_lapangan' => 'Titik A',
    ])->assertStatus(200);

    // Check-out di lokasi 2 (misal pindah titik penugasan)
    $responseOut = $this->actingAs($this->userPppk)->postJson(route('attendance.checkout'), [
        'latitude' => -6.5100,
        'longitude' => 107.8300,
        'accuracy' => 8,
        'selfie' => $file2,
    ]);

    $responseOut->assertStatus(200);
    $responseOut->assertJson(['success' => true]);

    $attendance = Attendance::where('employee_id', $this->employee->id)->first();
    expect($attendance->jam_pulang)->not->toBeNull();
    expect((float)$attendance->latitude_pulang)->toEqual(-6.5100);
    expect((float)$attendance->longitude_pulang)->toEqual(107.8300);
});

test('admin dapat melihat detail absensi dinas lapangan dengan tautan sharelok google maps', function () {
    $attendance = Attendance::create([
        'employee_id' => $this->employee->id,
        'tanggal' => now()->toDateString(),
        'jam_masuk' => '08:00:00',
        'latitude_masuk' => -6.4950,
        'longitude_masuk' => 107.8200,
        'accuracy_masuk' => 10,
        'status' => 'dinas',
        'keterangan' => 'Dinas Lapangan (Sharelok): Survei Wilayah',
    ]);

    $response = $this->actingAs($this->userAdmin)->get(route('admin.attendance-monitoring.show', $attendance));
    $response->assertStatus(200);
    $response->assertSee('Buka Sharelok (Google Maps)');
    $response->assertSee('https://www.google.com/maps?q=-6.495,107.82', false);
});
