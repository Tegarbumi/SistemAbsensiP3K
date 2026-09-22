<?php

use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Carbon::setTestNow(Carbon::create(2026, 9, 22, 7, 35, 0)); // Selasa, jam 07:35

    // Unit 1: Disarpus
    $this->unitDisarpus = Unit::create([
        'kode' => 'DISARPUS-RESET',
        'nama' => 'Dinas Kearsipan dan Perpustakaan',
        'status' => true,
        'is_dinas_lapangan' => false,
    ]);

    $this->officeDisarpus = OfficeLocation::create([
        'unit_id' => $this->unitDisarpus->id,
        'nama' => 'Kantor Disarpus',
        'alamat' => 'Jl. MT Haryono No. 9, Subang',
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'radius_meter' => 100,
        'status' => true,
    ]);

    // Unit 2: Dinas Lapangan
    $this->unitLapangan = Unit::create([
        'kode' => 'LAPANGAN-RESET',
        'nama' => 'Unit Pelaksana Teknis Dinas Lapangan',
        'status' => true,
        'is_dinas_lapangan' => true,
    ]);

    // Work Schedule
    $this->schedule = WorkSchedule::create([
        'nama' => 'Jadwal Reguler',
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

    // User & Employee
    $this->user = User::create([
        'name' => 'Ahmad Fauzi',
        'email' => 'ahmad.fauzi@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->employee = Employee::create([
        'user_id' => $this->user->id,
        'unit_id' => $this->unitDisarpus->id,
        'nip' => '199505052024211005',
        'nomor_pppk' => 'PPPK-RESET-001',
        'nama' => 'Ahmad Fauzi',
        'jabatan' => 'Pranata Komputer',
        'status' => true,
    ]);
});

afterEach(function () {
    Carbon::setTestNow();
});

test('pegawai dapat melakukan reset absensi hari ini jika terdapat kesalahan input', function () {
    $file = UploadedFile::fake()->image('selfie_masuk.jpg');

    // Pegawai check in terlebih dahulu
    $this->actingAs($this->user)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 10,
        'selfie' => $file,
        'unit_id' => $this->unitDisarpus->id,
    ]);

    expect(Attendance::where('employee_id', $this->employee->id)->count())->toBe(1);

    // Lakukan reset absensi
    $response = $this->actingAs($this->user)->postJson(route('attendance.reset'), [
        'alasan' => 'Salah pilih unit dan foto selfie buram',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    // Pastikan data absensi hari ini sudah terhapus
    expect(Attendance::where('employee_id', $this->employee->id)->count())->toBe(0);
});

test('reset absensi menghapus file foto selfie dari storage dan riwayat lokasi gps', function () {
    $fileMasuk = UploadedFile::fake()->image('selfie_masuk.jpg');

    $this->actingAs($this->user)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 10,
        'selfie' => $fileMasuk,
        'unit_id' => $this->unitDisarpus->id,
    ]);

    $attendance = Attendance::where('employee_id', $this->employee->id)->first();
    expect($attendance)->not->toBeNull();
    $selfieMasukPath = $attendance->selfie_masuk;

    // Tambah riwayat lokasi GPS dummy
    AttendanceLocation::create([
        'attendance_id' => $attendance->id,
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'recorded_at' => now(),
    ]);

    // Verifikasi file selfie masuk ada di storage
    Storage::disk('public')->assertExists($selfieMasukPath);
    $this->assertDatabaseHas('attendance_locations', ['attendance_id' => $attendance->id]);

    // Reset absensi
    $response = $this->actingAs($this->user)->postJson(route('attendance.reset'), [
        'alasan' => 'Koreksi selfie dan koordinat',
    ]);

    $response->assertStatus(200);

    // Verifikasi file terhapus dan riwayat lokasi terhapus
    Storage::disk('public')->assertMissing($selfieMasukPath);
    $this->assertDatabaseMissing('attendance_locations', ['attendance_id' => $attendance->id]);
});

test('reset absensi mencatat riwayat audit log dengan tindakan attendance_reset dan snapshot data', function () {
    $file = UploadedFile::fake()->image('selfie.jpg');

    $this->actingAs($this->user)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 10,
        'selfie' => $file,
        'unit_id' => $this->unitDisarpus->id,
    ]);

    $this->actingAs($this->user)->postJson(route('attendance.reset'), [
        'alasan' => 'Salah pilih unit kerja',
    ]);

    // Verifikasi AuditLog tercatat
    $log = AuditLog::where('action', 'attendance_reset')
        ->where('user_id', $this->user->id)
        ->first();

    expect($log)->not->toBeNull();
    expect($log->details)->toBeArray();
    expect($log->details['alasan'])->toBe('Salah pilih unit kerja');
    expect($log->details['employee'])->toBe('Ahmad Fauzi');
    expect($log->details['previous_attendance'])->toHaveKey('unit_id');
});

test('pegawai dapat langsung melakukan check-in ulang setelah me-reset absensinya', function () {
    $file1 = UploadedFile::fake()->image('selfie1.jpg');

    // 1. Check in pertama di Disarpus
    $this->actingAs($this->user)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 10,
        'selfie' => $file1,
        'unit_id' => $this->unitDisarpus->id,
    ]);

    $att1 = Attendance::where('employee_id', $this->employee->id)->first();
    expect($att1->unit_id)->toBe($this->unitDisarpus->id);

    // 2. Reset absensi
    $this->actingAs($this->user)->postJson(route('attendance.reset'), [
        'alasan' => 'Harusnya dinas lapangan di lokasi survei',
    ]);

    // 3. Check in kedua (absen ulang) di Dinas Lapangan
    $file2 = UploadedFile::fake()->image('selfie2.jpg');
    $checkInAgain = $this->actingAs($this->user)->postJson(route('attendance.checkin'), [
        'latitude' => -6.6000000,
        'longitude' => 107.8000000,
        'accuracy' => 15,
        'selfie' => $file2,
        'unit_id' => $this->unitLapangan->id,
        'lokasi_lapangan' => 'Kecamatan Pagaden Barat',
    ]);

    $checkInAgain->assertStatus(200);
    $checkInAgain->assertJson(['success' => true]);

    // Pastikan tersimpan data absensi baru dengan unit dinas lapangan
    $att2 = Attendance::where('employee_id', $this->employee->id)->first();
    expect($att2)->not->toBeNull();
    expect($att2->unit_id)->toBe($this->unitLapangan->id);
    expect($att2->keterangan)->toContain('Kecamatan Pagaden Barat');
});

test('reset absensi ditolak jika belum ada data absensi hari ini', function () {
    $response = $this->actingAs($this->user)->postJson(route('attendance.reset'), [
        'alasan' => 'Belum pernah absen tapi ingin reset',
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'message' => 'Tidak ada data absensi hari ini yang dapat di-reset.',
    ]);
});

test('halaman absensi memuat modal dan tombol absen ulang ketika sudah check-in', function () {
    $file = UploadedFile::fake()->image('selfie.jpg');

    $this->actingAs($this->user)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 10,
        'selfie' => $file,
        'unit_id' => $this->unitDisarpus->id,
    ]);

    $response = $this->actingAs($this->user)->get(route('attendance.index'));

    $response->assertStatus(200);
    $response->assertSee('modal-reset-attendance');
    $response->assertSee('Absen Ulang / Koreksi Absen');
});
