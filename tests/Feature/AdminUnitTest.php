<?php

use App\Models\Employee;
use App\Models\OfficeLocation;
use App\Models\Unit;
use App\Models\User;

beforeEach(function () {
    // Admin
    $this->admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin.unit@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // PPPK
    $this->pppk = User::create([
        'name' => 'PPPK Test',
        'email' => 'pppk.unit@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->unit = Unit::create([
        'kode' => 'UNIT-TEST-1',
        'nama' => 'Bidang Pengelolaan Arsip',
        'alamat' => 'Jl. MT Haryono No. 9',
        'status' => true,
    ]);
});

test('guest tidak dapat mengakses manajemen unit', function () {
    $response = $this->get(route('admin.units.index'));
    $response->assertRedirect(route('login'));
});

test('pppk tidak dapat mengakses manajemen unit admin', function () {
    $response = $this->actingAs($this->pppk)->get(route('admin.units.index'));
    $response->assertStatus(403);
});

test('admin dapat melihat daftar unit kerja', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.units.index'));
    $response->assertStatus(200);
    $response->assertSee('Bidang Pengelolaan Arsip');
    $response->assertSee('UNIT-TEST-1');
});

test('admin dapat memfilter dan mencari unit', function () {
    Unit::create([
        'kode' => 'UNIT-PUS',
        'nama' => 'Bidang Layanan Perpustakaan',
        'status' => false,
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.units.index', ['search' => 'Perpustakaan']));
    $response->assertStatus(200);
    $response->assertSee('Bidang Layanan Perpustakaan');
    $response->assertDontSee('Bidang Pengelolaan Arsip');

    // Filter status nonaktif
    $responseStatus = $this->actingAs($this->admin)->get(route('admin.units.index', ['status' => '0']));
    $responseStatus->assertStatus(200);
    $responseStatus->assertSee('Bidang Layanan Perpustakaan');
    $responseStatus->assertDontSee('Bidang Pengelolaan Arsip');
});

test('admin dapat membuka halaman form tambah unit', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.units.create'));
    $response->assertStatus(200);
    $response->assertSee('Tambah Unit / Bagian Kerja');
});

test('admin dapat menambahkan unit kerja baru dengan data valid', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.units.store'), [
        'kode' => 'bid-dokumen',
        'nama' => 'Bidang Pengolahan Bahan Pustaka',
        'alamat' => 'Gedung Perpustakaan Lantai 2',
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.units.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('units', [
        'kode' => 'BID-DOKUMEN',
        'nama' => 'Bidang Pengolahan Bahan Pustaka',
        'alamat' => 'Gedung Perpustakaan Lantai 2',
        'status' => true,
    ]);
});

test('validasi penambahan unit menolak kode duplikat atau kolom kosong', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.units.store'), [
        'kode' => 'UNIT-TEST-1',
        'nama' => '',
        'status' => '',
    ]);

    $response->assertSessionHasErrors(['kode', 'nama', 'status']);
});

test('admin dapat mengedit data unit', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.units.update', $this->unit), [
        'kode' => 'UNIT-TEST-1',
        'nama' => 'Bidang Pengelolaan Arsip Dinamis & Statis',
        'alamat' => 'Gedung Arsip Utama Subang',
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.units.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('units', [
        'id' => $this->unit->id,
        'nama' => 'Bidang Pengelolaan Arsip Dinamis & Statis',
        'alamat' => 'Gedung Arsip Utama Subang',
    ]);
});

test('admin dapat melakukan toggle status unit', function () {
    expect($this->unit->status)->toBeTrue();

    $response = $this->actingAs($this->admin)->patch(route('admin.units.toggle-status', $this->unit));
    $response->assertSessionHas('success');

    $this->unit->refresh();
    expect((bool)$this->unit->status)->toBeFalse();
});

test('unit tidak dapat dihapus jika masih memiliki pegawai terikat', function () {
    Employee::create([
        'user_id' => $this->pppk->id,
        'unit_id' => $this->unit->id,
        'nip' => '199505052023211005',
        'nama' => 'Pegawai Arsip',
        'jabatan' => 'Arsiparis',
        'status' => true,
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.units.destroy', $this->unit));
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('units', ['id' => $this->unit->id]);
});

test('unit tidak dapat dihapus jika masih memiliki lokasi kantor terikat', function () {
    OfficeLocation::create([
        'unit_id' => $this->unit->id,
        'nama' => 'Kantor Arsip',
        'alamat' => 'Jl. MT Haryono',
        'latitude' => -6.5683,
        'longitude' => 107.7634,
        'radius_meter' => 100,
        'status' => true,
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.units.destroy', $this->unit));
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('units', ['id' => $this->unit->id]);
});

test('unit tanpa relasi dapat dihapus oleh admin', function () {
    $cleanUnit = Unit::create([
        'kode' => 'UNIT-BERSIH',
        'nama' => 'Unit Tanpa Relasi',
        'status' => true,
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.units.destroy', $cleanUnit));
    $response->assertRedirect(route('admin.units.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('units', ['id' => $cleanUnit->id]);
});
