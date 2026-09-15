<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    $this->unit = Unit::create([
        'kode' => 'UNIT-HIST',
        'nama' => 'Unit Kearsipan',
        'status' => true,
    ]);

    $this->office = OfficeLocation::create([
        'unit_id' => $this->unit->id,
        'nama' => 'Kantor Disarpus',
        'alamat' => 'Jl. MT Haryono No. 9',
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'radius_meter' => 100,
        'status' => true,
    ]);

    // PPPK 1
    $this->user1 = User::create([
        'name' => 'PPPK Satu',
        'email' => 'pppk1@test.com',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);
    $this->employee1 = Employee::create([
        'user_id' => $this->user1->id,
        'unit_id' => $this->unit->id,
        'nip' => '199101012023211001',
        'nama' => 'PPPK Satu',
        'jabatan' => 'Arsiparis',
        'status' => true,
    ]);

    // PPPK 2
    $this->user2 = User::create([
        'name' => 'PPPK Dua',
        'email' => 'pppk2@test.com',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);
    $this->employee2 = Employee::create([
        'user_id' => $this->user2->id,
        'unit_id' => $this->unit->id,
        'nip' => '199202022023211002',
        'nama' => 'PPPK Dua',
        'jabatan' => 'Pustakawan',
        'status' => true,
    ]);

    // Admin
    $this->admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);
});

test('guest tidak dapat mengakses riwayat absensi', function () {
    $response = $this->get(route('attendance.history.index'));
    $response->assertRedirect(route('login'));
});

test('admin tidak dapat mengakses riwayat absensi pppk', function () {
    $response = $this->actingAs($this->admin)->get(route('attendance.history.index'));
    $response->assertStatus(403);
});

test('pppk dapat melihat riwayat absensi miliknya sendiri', function () {
    // Buat data absensi untuk employee 1
    $att1 = Attendance::create([
        'employee_id' => $this->employee1->id,
        'tanggal' => Carbon::now()->toDateString(),
        'jam_masuk' => '07:25:00',
        'jam_pulang' => '16:05:00',
        'status' => 'hadir',
    ]);

    // Buat data absensi untuk employee 2
    $att2 = Attendance::create([
        'employee_id' => $this->employee2->id,
        'tanggal' => Carbon::now()->subDay()->toDateString(),
        'jam_masuk' => '07:50:00',
        'status' => 'terlambat',
    ]);

    $response = $this->actingAs($this->user1)->get(route('attendance.history.index'));
    $response->assertStatus(200);
    $response->assertSee('07:25:00');
    // Tidak boleh melihat jam masuk milik employee 2
    $response->assertDontSee('07:50:00');
});

test('pppk dapat memfilter riwayat absensi berdasarkan status', function () {
    Attendance::create([
        'employee_id' => $this->employee1->id,
        'tanggal' => '2026-05-10',
        'jam_masuk' => '07:20:00',
        'status' => 'hadir',
    ]);

    Attendance::create([
        'employee_id' => $this->employee1->id,
        'tanggal' => '2026-05-11',
        'jam_masuk' => '07:55:00',
        'status' => 'terlambat',
    ]);

    $response = $this->actingAs($this->user1)->get(route('attendance.history.index', [
        'bulan' => '5',
        'tahun' => '2026',
        'status' => 'hadir',
    ]));

    $response->assertStatus(200);
    $response->assertSee('07:20:00');
    $response->assertDontSee('07:55:00');
});

test('pppk dapat melihat detail absensi miliknya', function () {
    $att = Attendance::create([
        'employee_id' => $this->employee1->id,
        'tanggal' => '2026-06-15',
        'jam_masuk' => '07:22:00',
        'jam_pulang' => '16:02:00',
        'latitude_masuk' => -6.5683000,
        'longitude_masuk' => 107.7634000,
        'distance_masuk' => 25.5,
        'status' => 'hadir',
    ]);

    $response = $this->actingAs($this->user1)->get(route('attendance.history.show', $att->id));
    $response->assertStatus(200);
    $response->assertSee('07:22:00');
    $response->assertSee('16:02:00');
    $response->assertSee('Peta Titik Lokasi Presensi');
});

test('pppk ditolak jika mencoba mengakses detail absensi milik pppk lain', function () {
    // Absensi milik employee 2
    $att2 = Attendance::create([
        'employee_id' => $this->employee2->id,
        'tanggal' => '2026-06-15',
        'jam_masuk' => '07:22:00',
        'status' => 'hadir',
    ]);

    // User 1 mencoba membuka absensi employee 2
    $response = $this->actingAs($this->user1)->get(route('attendance.history.show', $att2->id));
    $response->assertStatus(403);
});
