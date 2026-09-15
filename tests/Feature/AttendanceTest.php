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

    // Buat data unit
    $this->unit = Unit::create([
        'kode' => 'TEST-UNIT',
        'nama' => 'Dinas Kearsipan dan Perpustakaan Test',
        'status' => true,
    ]);

    // Buat lokasi kantor: -6.5683000, 107.7634000, radius 100m
    $this->office = OfficeLocation::create([
        'unit_id' => $this->unit->id,
        'nama' => 'Kantor Disarpus Test',
        'alamat' => 'Jl. MT Haryono No. 9',
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'radius_meter' => 100,
        'status' => true,
    ]);

    // Buat jadwal kerja
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

    // User PPPK
    $this->userPppk = User::create([
        'name' => 'Pegawai PPPK Test',
        'email' => 'pppk.test@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->employee = Employee::create([
        'user_id' => $this->userPppk->id,
        'unit_id' => $this->unit->id,
        'nip' => '199001012023211001',
        'nomor_pppk' => 'PPPK-TEST-001',
        'nama' => 'Pegawai PPPK Test',
        'jabatan' => 'Arsiparis',
        'status' => true,
    ]);

    // User Admin
    $this->userAdmin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin.test@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
});

test('guest tidak dapat mengakses halaman absensi', function () {
    $response = $this->get(route('attendance.index'));
    $response->assertRedirect(route('login'));
});

test('admin tidak dapat mengakses halaman absensi pppk', function () {
    $response = $this->actingAs($this->userAdmin)->get(route('attendance.index'));
    $response->assertStatus(403);
});

test('pppk dapat mengakses halaman absensi', function () {
    $response = $this->actingAs($this->userPppk)->get(route('attendance.index'));
    $response->assertStatus(200);
    $response->assertSee('Pegawai PPPK Test');
    $response->assertSee('Dinas Kearsipan dan Perpustakaan Test');
    $response->assertSee('Belum Check-in');
});

test('endpoint status absensi mengembalikan json dengan waktu server', function () {
    $response = $this->actingAs($this->userPppk)->getJson(route('attendance.status'));
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'server_time',
        'has_checked_in',
        'has_checked_out',
    ]);
    $response->assertJson([
        'has_checked_in' => false,
        'has_checked_out' => false,
    ]);
});

test('check-in ditolak jika berada di luar radius kantor', function () {
    $file = UploadedFile::fake()->image('selfie.jpg');

    // Koordinat jauh (Jakarta: -6.2088, 106.8456)
    $response = $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'accuracy' => 10,
        'selfie' => $file,
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
    ]);
    $this->assertStringContainsString('di luar radius', $response->json('message'));
});

test('check-in ditolak jika selfie tidak dilampirkan', function () {
    $response = $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 10,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['selfie']);
});

test('check-in berhasil jika di dalam radius dengan selfie', function () {
    $file = UploadedFile::fake()->image('selfie_masuk.jpg');

    // Koordinat tepat di kantor Disarpus
    $response = $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $file,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    $attendance = Attendance::where('employee_id', $this->employee->id)->first();
    expect($attendance)->not->toBeNull();
    expect($attendance->jam_masuk)->not->toBeNull();
    expect($attendance->selfie_masuk)->not->toBeNull();
    expect($attendance->distance_masuk)->not->toBeNull();

    // Pastikan file selfie tersimpan di disk public
    Storage::disk('public')->assertExists($attendance->selfie_masuk);

    // Pastikan attendance_locations tersimpan
    $location = AttendanceLocation::where('attendance_id', $attendance->id)->first();
    expect($location)->not->toBeNull();
    expect((float)$location->latitude)->toEqual(-6.5683000);
});

test('check-in ditolak jika sudah check-in pada hari yang sama', function () {
    $file1 = UploadedFile::fake()->image('selfie1.jpg');
    $file2 = UploadedFile::fake()->image('selfie2.jpg');

    // Check-in pertama
    $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $file1,
    ])->assertStatus(200);

    // Check-in kedua harus ditolak
    $response2 = $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $file2,
    ]);

    $response2->assertStatus(422);
    $response2->assertJson([
        'success' => false,
        'message' => 'Anda sudah melakukan check-in hari ini.',
    ]);
});

test('check-out ditolak jika belum melakukan check-in', function () {
    $file = UploadedFile::fake()->image('selfie_pulang.jpg');

    $response = $this->actingAs($this->userPppk)->postJson(route('attendance.checkout'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $file,
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'message' => 'Anda belum melakukan absensi masuk.',
    ]);
});

test('check-out berhasil setelah check-in', function () {
    $fileMasuk = UploadedFile::fake()->image('selfie_masuk.jpg');
    $filePulang = UploadedFile::fake()->image('selfie_pulang.jpg');

    // Check-in
    $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $fileMasuk,
    ])->assertStatus(200);

    // Check-out
    $response = $this->actingAs($this->userPppk)->postJson(route('attendance.checkout'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $filePulang,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    $attendance = Attendance::where('employee_id', $this->employee->id)->first();
    expect($attendance->jam_pulang)->not->toBeNull();
    expect($attendance->selfie_pulang)->not->toBeNull();
    expect($attendance->distance_pulang)->not->toBeNull();

    // Pastikan kedua file tersimpan di disk public
    Storage::disk('public')->assertExists($attendance->selfie_masuk);
    Storage::disk('public')->assertExists($attendance->selfie_pulang);

    // Pastikan ada 2 lokasi recorded
    expect(AttendanceLocation::where('attendance_id', $attendance->id)->count())->toBe(2);
});

test('check-out ditolak jika sudah pernah check-out pada hari yang sama', function () {
    $fileMasuk = UploadedFile::fake()->image('selfie_masuk.jpg');
    $filePulang1 = UploadedFile::fake()->image('selfie_pulang1.jpg');
    $filePulang2 = UploadedFile::fake()->image('selfie_pulang2.jpg');

    // Check-in
    $this->actingAs($this->userPppk)->postJson(route('attendance.checkin'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $fileMasuk,
    ])->assertStatus(200);

    // Check-out pertama
    $this->actingAs($this->userPppk)->postJson(route('attendance.checkout'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $filePulang1,
    ])->assertStatus(200);

    // Check-out kedua harus ditolak
    $response2 = $this->actingAs($this->userPppk)->postJson(route('attendance.checkout'), [
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'accuracy' => 5,
        'selfie' => $filePulang2,
    ]);

    $response2->assertStatus(422);
    $response2->assertJson([
        'success' => false,
        'message' => 'Anda sudah melakukan absensi pulang hari ini.',
    ]);
});
