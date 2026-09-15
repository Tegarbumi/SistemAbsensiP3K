<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    $this->unit = Unit::create([
        'kode' => 'UNIT-ADM',
        'nama' => 'Unit Pelayanan Kearsipan',
        'status' => true,
    ]);

    // Admin
    $this->admin = User::create([
        'name' => 'Admin Super',
        'email' => 'admin.test@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // PPPK
    $this->pppk = User::create([
        'name' => 'Pegawai PPPK',
        'email' => 'pppk.test@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);
    $this->employee = Employee::create([
        'user_id' => $this->pppk->id,
        'unit_id' => $this->unit->id,
        'nip' => '199001012023211001',
        'nama' => 'Pegawai PPPK',
        'jabatan' => 'Arsiparis',
        'status' => true,
    ]);
});

test('guest tidak dapat mengakses manajemen pppk admin', function () {
    $response = $this->get(route('admin.employees.index'));
    $response->assertRedirect(route('login'));
});

test('pppk tidak dapat mengakses manajemen pppk admin', function () {
    $response = $this->actingAs($this->pppk)->get(route('admin.employees.index'));
    $response->assertStatus(403);
});

test('admin dapat melihat daftar pegawai pppk', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.employees.index'));
    $response->assertStatus(200);
    $response->assertSee('Pegawai PPPK');
    $response->assertSee('199001012023211001');
});

test('admin dapat menambah pegawai pppk baru dengan otomatis membuat user pppk', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.employees.store'), [
        'nama' => 'Budi Santoso, S.Kom',
        'nip' => '199505052023211005',
        'nomor_pppk' => 'PPPK-2023-0099',
        'jabatan' => 'Pranata Komputer',
        'unit_id' => $this->unit->id,
        'email' => 'budi.komputer@subang.go.id',
        'password' => 'secret123',
    ]);

    $response->assertRedirect(route('admin.employees.index'));
    $response->assertSessionHas('success');

    // Pastikan user baru dibuat dengan role pppk
    $user = User::where('email', 'budi.komputer@subang.go.id')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('pppk');
    expect($user->name)->toBe('Budi Santoso, S.Kom');

    // Pastikan employee baru terhubung ke user
    $employee = Employee::where('nip', '199505052023211005')->first();
    expect($employee)->not->toBeNull();
    expect($employee->user_id)->toBe($user->id);
    expect($employee->unit_id)->toBe($this->unit->id);
    expect((bool)$employee->status)->toBeTrue();
});

test('admin ditolak menambah pppk dengan nip yang sudah ada', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.employees.store'), [
        'nama' => 'Duplikat NIP',
        'nip' => '199001012023211001', // NIP yang sudah ada
        'unit_id' => $this->unit->id,
        'email' => 'unik@subang.go.id',
    ]);

    $response->assertSessionHasErrors(['nip']);
});

test('admin dapat melihat detail pegawai pppk', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.employees.show', $this->employee->id));
    $response->assertStatus(200);
    $response->assertSee('Pegawai PPPK');
    $response->assertSee('199001012023211001');
    $response->assertSee('Riwayat Presensi Terbaru');
});

test('admin dapat mengubah data pppk', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.employees.update', $this->employee->id), [
        'nama' => 'Pegawai PPPK Updated',
        'nip' => '199001012023211001',
        'nomor_pppk' => 'PPPK-UPDATED',
        'jabatan' => 'Arsiparis Madya',
        'unit_id' => $this->unit->id,
        'email' => 'pppk.test@subang.go.id',
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.employees.index'));
    $response->assertSessionHas('success');

    $this->employee->refresh();
    expect($this->employee->nama)->toBe('Pegawai PPPK Updated');
    expect($this->employee->jabatan)->toBe('Arsiparis Madya');
});

test('admin dapat mengubah status aktif nonaktif pppk', function () {
    expect((bool)$this->employee->status)->toBeTrue();

    // Toggle menjadi nonaktif
    $response = $this->actingAs($this->admin)->patch(route('admin.employees.toggle-status', $this->employee->id));
    $response->assertRedirect();
    $this->employee->refresh();
    expect((bool)$this->employee->status)->toBeFalse();

    // Toggle kembali menjadi aktif
    $response2 = $this->actingAs($this->admin)->patch(route('admin.employees.toggle-status', $this->employee->id));
    $response2->assertRedirect();
    $this->employee->refresh();
    expect((bool)$this->employee->status)->toBeTrue();
});

test('admin ditolak menghapus pppk yang memiliki histori absensi', function () {
    // Buat absensi untuk employee
    Attendance::create([
        'employee_id' => $this->employee->id,
        'tanggal' => '2026-07-01',
        'jam_masuk' => '07:30:00',
        'status' => 'hadir',
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.employees.destroy', $this->employee->id));
    $response->assertRedirect();
    $response->assertSessionHas('error');

    // Pastikan employee TIDAK terhapus
    expect(Employee::find($this->employee->id))->not->toBeNull();
});

test('admin dapat menghapus pppk yang belum memiliki histori absensi', function () {
    // Pegawai tanpa histori absensi
    $userBaru = User::create([
        'name' => 'PPPK Baru',
        'email' => 'baru@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);
    $empBaru = Employee::create([
        'user_id' => $userBaru->id,
        'unit_id' => $this->unit->id,
        'nip' => '199912122023211099',
        'nama' => 'PPPK Baru',
        'status' => true,
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.employees.destroy', $empBaru->id));
    $response->assertRedirect(route('admin.employees.index'));
    $response->assertSessionHas('success');

    // Pastikan terhapus
    expect(Employee::find($empBaru->id))->toBeNull();
    expect(User::find($userBaru->id))->toBeNull();
});
