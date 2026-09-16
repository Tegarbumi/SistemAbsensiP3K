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
        'email' => 'admin.gps@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // PPPK
    $this->pppk = User::create([
        'name' => 'PPPK Test',
        'email' => 'pppk.gps@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->unit = Unit::create([
        'kode' => 'UNIT-GPS',
        'nama' => 'Bidang Arsip Dinamis',
        'status' => true,
    ]);

    $this->employee = Employee::create([
        'user_id' => $this->pppk->id,
        'unit_id' => $this->unit->id,
        'nip' => '199301012024211003',
        'nama' => 'Dewi Lestari',
        'jabatan' => 'Arsiparis Terampil',
        'status' => true,
    ]);

    $this->office = OfficeLocation::create([
        'unit_id' => $this->unit->id,
        'nama' => 'Kantor Disarpus',
        'alamat' => 'Jl. MT Haryono No. 9 Subang',
        'latitude' => -6.5683,
        'longitude' => 107.7634,
        'radius_meter' => 100,
        'status' => true,
    ]);

    $this->attendance = Attendance::create([
        'employee_id' => $this->employee->id,
        'tanggal' => Carbon::today()->toDateString(),
        'jam_masuk' => '07:28:00',
        'latitude_masuk' => -6.56832,
        'longitude_masuk' => 107.76342,
        'accuracy_masuk' => 8,
        'distance_masuk' => 12,
        'selfie_masuk' => 'selfies/test.jpg',
        'status' => 'hadir',
    ]);
});

test('guest tidak dapat mengakses admin monitoring gps', function () {
    $response = $this->get(route('admin.gps-monitoring.index'));
    $response->assertRedirect(route('login'));
});

test('pppk tidak dapat mengakses admin monitoring gps', function () {
    $response = $this->actingAs($this->pppk)->get(route('admin.gps-monitoring.index'));
    $response->assertStatus(403);
});

test('admin dapat melihat peta monitoring gps presensi', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.gps-monitoring.index'));
    $response->assertStatus(200);
    $response->assertSee('Monitoring GPS Presensi PPPK');
    $response->assertSee('Kantor Disarpus');
    $response->assertSee('Dewi Lestari');
});

test('admin dapat memfilter monitoring gps berdasarkan tanggal', function () {
    $yesterday = Carbon::yesterday()->toDateString();
    $response = $this->actingAs($this->admin)->get(route('admin.gps-monitoring.index', [
        'tanggal' => $yesterday,
    ]));
    $response->assertStatus(200);
    // Tidak ada marker Dewi Lestari pada tanggal kemarin
    $response->assertDontSee('Dewi Lestari');
});
