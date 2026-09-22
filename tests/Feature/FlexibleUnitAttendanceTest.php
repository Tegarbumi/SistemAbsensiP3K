<?php

use App\Models\Attendance;
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
    Carbon::setTestNow(Carbon::create(2026, 9, 22, 7, 35, 0)); // Selasa, jam 07:35 (tepat waktu)

    // Unit 1: Disarpus (Kantor 1)
    $this->unitDisarpus = Unit::create([
        'kode' => 'DISARPUS-TEST',
        'nama' => 'Dinas Kearsipan dan Perpustakaan Test',
        'status' => true,
        'is_dinas_lapangan' => false,
    ]);

    $this->officeDisarpus = OfficeLocation::create([
        'unit_id' => $this->unitDisarpus->id,
        'nama' => 'Kantor Disarpus Test',
        'alamat' => 'Jl. MT Haryono No. 9, Subang',
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'radius_meter' => 100,
        'status' => true,
    ]);

    // Unit 2: Perpusda (Kantor 2)
    $this->unitPerpusda = Unit::create([
        'kode' => 'PERPUSDA-TEST',
        'nama' => 'Perpustakaan Daerah Test',
        'status' => true,
        'is_dinas_lapangan' => false,
    ]);

    $this->officePerpusda = OfficeLocation::create([
        'unit_id' => $this->unitPerpusda->id,
        'nama' => 'Kantor Perpustakaan Daerah Test',
        'alamat' => 'Jl. Veteran No. 1, Subang',
        'latitude' => -6.5710000,
        'longitude' => 107.7520000,
        'radius_meter' => 80,
        'status' => true,
    ]);

    // Unit 3: Dinas Lapangan (Sharelok GPS)
    $this->unitLapangan = Unit::create([
        'kode' => 'LAPANGAN-TEST',
        'nama' => 'Unit Pelaksana Teknis Dinas Lapangan Test',
        'status' => true,
        'is_dinas_lapangan' => true,
    ]);

    // Jadwal Kerja
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

    // Pegawai terdaftar di Unit Disarpus
    $this->userPppk = User::create([
        'name' => 'Budi Santoso',
        'email' => 'budi.santoso@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->employee = Employee::create([
        'user_id' => $this->userPppk->id,
        'unit_id' => $this->unitDisarpus->id,
        'nip' => '199201012023211002',
        'nomor_pppk' => 'PPPK-TEST-002',
        'nama' => 'Budi Santoso',
        'jabatan' => 'Arsiparis Terampil',
        'status' => true,
    ]);

    // Admin
    $this->userAdmin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin.flex@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
});

afterEach(function () {
    Carbon::setTestNow();
});

test('halaman absensi menampilkan pilihan semua unit kerja aktif', function () {
    $response = $this->actingAs($this->userPppk)->get(route('attendance.index'));
    
    $response->assertStatus(200);
    $response->assertSee('Pilih Unit Kerja / Penugasan Absensi');
    $response->assertSee('Dinas Kearsipan dan Perpustakaan Test');
    $response->assertSee('Perpustakaan Daerah Test');
    $response->assertSee('Unit Pelaksana Teknis Dinas Lapangan Test');
});

test('pegawai terdaftar di Disarpus dapat memilih absen di Perpustakaan Daerah dan divalidasi sesuai radius kantor Perpusda', function () {
    $file = UploadedFile::fake()->image('selfie.jpg');

    // Posisi di kantor Perpustakaan Daerah (-6.5710000, 107.7520000)
    $response = $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5710000,
        'longitude' => 107.7520000,
        'accuracy' => 10,
        'selfie' => $file,
        'unit_id' => $this->unitPerpusda->id,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $attendance = Attendance::where('employee_id', $this->employee->id)->first();
    expect($attendance)->not->toBeNull();
    expect($attendance->unit_id)->toEqual($this->unitPerpusda->id);
    expect($attendance->status)->toEqual('hadir');
    expect((float)$attendance->distance_masuk)->toBeLessThan(10);
});

test('pegawai dapat memilih unit dinas lapangan dengan koordinat bebas dan otomatis berstatus dinas', function () {
    $file = UploadedFile::fake()->image('selfie.jpg');

    // Koordinat sembarang di luar kantor
    $response = $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5000000,
        'longitude' => 107.8000000,
        'accuracy' => 15,
        'selfie' => $file,
        'unit_id' => $this->unitLapangan->id,
        'lokasi_lapangan' => 'Pengawasan Kearsipan di Kantor Desa',
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
    expect($attendance->unit_id)->toEqual($this->unitLapangan->id);
    expect($attendance->status)->toEqual('dinas');
    expect($attendance->keterangan)->toContain('Dinas Lapangan (Sharelok)');
});

test('pegawai yang sudah check-in terkunci pada unit yang dipilih untuk hari itu', function () {
    $fileMasuk = UploadedFile::fake()->image('selfie_masuk.jpg');
    $filePulang = UploadedFile::fake()->image('selfie_pulang.jpg');

    // Check-in di Perpusda
    $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5710000,
        'longitude' => 107.7520000,
        'accuracy' => 10,
        'selfie' => $fileMasuk,
        'unit_id' => $this->unitPerpusda->id,
    ])->assertStatus(200);

    // Buka halaman index - dropdown harus disabled
    $responseIndex = $this->actingAs($this->userPppk)->get(route('attendance.index'));
    $responseIndex->assertStatus(200);
    $responseIndex->assertSee('disabled', false);

    // Check-out otomatis mengacu ke unit yang dipilih saat check-in
    $responseCheckout = $this->actingAs($this->userPppk)->postJson(route('attendance.checkout'), [
        'latitude' => -6.5710000,
        'longitude' => 107.7520000,
        'accuracy' => 10,
        'selfie' => $filePulang,
    ]);

    $responseCheckout->assertStatus(200);
    $responseCheckout->assertJson(['success' => true]);

    $attendance = Attendance::where('employee_id', $this->employee->id)->first();
    expect($attendance->jam_pulang)->not->toBeNull();
    expect($attendance->unit_id)->toEqual($this->unitPerpusda->id);
});

test('admin monitoring menampilkan unit absensi yang dipilih dan unit induk pegawai', function () {
    // Pegawai asal Disarpus tapi absen di Perpusda
    $attendance = Attendance::create([
        'employee_id' => $this->employee->id,
        'unit_id' => $this->unitPerpusda->id,
        'tanggal' => now()->toDateString(),
        'jam_masuk' => '07:45:00',
        'latitude_masuk' => -6.5710000,
        'longitude_masuk' => 107.7520000,
        'accuracy_masuk' => 10,
        'status' => 'hadir',
    ]);

    // Admin index
    $responseIndex = $this->actingAs($this->userAdmin)->get(route('admin.attendance-monitoring.index'));
    $responseIndex->assertStatus(200);
    $responseIndex->assertSee('Perpustakaan Daerah Test');
    $responseIndex->assertSee('(Induk: Dinas Kearsipan dan Perpustakaan Test)');

    // Admin show
    $responseShow = $this->actingAs($this->userAdmin)->get(route('admin.attendance-monitoring.show', $attendance));
    $responseShow->assertStatus(200);
    $responseShow->assertSee('Unit Induk: Dinas Kearsipan dan Perpustakaan Test');
    $responseShow->assertSee('Unit Absensi: Perpustakaan Daerah Test');
});
