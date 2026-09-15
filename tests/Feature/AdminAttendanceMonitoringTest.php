<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    // Admin
    $this->admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin.mon@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // PPPK
    $this->pppk = User::create([
        'name' => 'PPPK Test',
        'email' => 'pppk.mon@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->unit = Unit::create([
        'kode' => 'UNIT-MON',
        'nama' => 'Bidang Layanan Arsip',
        'status' => true,
    ]);

    $this->employee = Employee::create([
        'user_id' => $this->pppk->id,
        'unit_id' => $this->unit->id,
        'nip' => '199201012024211002',
        'nama' => 'Budi Santoso',
        'jabatan' => 'Pranata Komputer',
        'status' => true,
    ]);

    $this->office = OfficeLocation::create([
        'unit_id' => $this->unit->id,
        'nama' => 'Kantor Disarpus',
        'latitude' => -6.5683,
        'longitude' => 107.7634,
        'radius_meter' => 100,
        'status' => true,
    ]);

    $this->todayAttendance = Attendance::create([
        'employee_id' => $this->employee->id,
        'tanggal' => Carbon::today()->toDateString(),
        'jam_masuk' => '07:25:00',
        'latitude_masuk' => -6.5683,
        'longitude_masuk' => 107.7634,
        'accuracy_masuk' => 10,
        'distance_masuk' => 15,
        'selfie_masuk' => 'selfies/test_masuk.jpg',
        'status' => 'hadir',
    ]);
});

test('guest tidak dapat mengakses admin monitoring absensi', function () {
    $response = $this->get(route('admin.attendance-monitoring.index'));
    $response->assertRedirect(route('login'));
});

test('pppk tidak dapat mengakses admin monitoring absensi', function () {
    $response = $this->actingAs($this->pppk)->get(route('admin.attendance-monitoring.index'));
    $response->assertStatus(403);
});

test('admin dapat melihat daftar monitoring absensi hari ini', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.attendance-monitoring.index'));
    $response->assertStatus(200);
    $response->assertSee('Budi Santoso');
    $response->assertSee('199201012024211002');
    $response->assertSee('07:25');
    $response->assertSee('Hadir');
});

test('admin dapat memfilter absensi berdasarkan tanggal, nama, unit, dan status', function () {
    // Filter nama yang cocok
    $response = $this->actingAs($this->admin)->get(route('admin.attendance-monitoring.index', [
        'search' => 'Budi',
    ]));
    $response->assertStatus(200);
    $response->assertSee('Budi Santoso');

    // Filter nama yang tidak cocok
    $responseNotFound = $this->actingAs($this->admin)->get(route('admin.attendance-monitoring.index', [
        'search' => 'NamaTidakAda',
    ]));
    $responseNotFound->assertStatus(200);
    $responseNotFound->assertDontSee('Budi Santoso');

    // Filter status cocok
    $responseStatus = $this->actingAs($this->admin)->get(route('admin.attendance-monitoring.index', [
        'status' => 'hadir',
    ]));
    $responseStatus->assertStatus(200);
    $responseStatus->assertSee('Budi Santoso');
});

test('admin dapat melihat detail absensi pegawai', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.attendance-monitoring.show', $this->todayAttendance));
    $response->assertStatus(200);
    $response->assertSee('Budi Santoso');
    $response->assertSee('07:25:00');
    $response->assertSee('Absensi Masuk (Check-In)');
    $response->assertSee('Kantor Disarpus');
});
