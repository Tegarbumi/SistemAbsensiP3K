<?php

use App\Models\OfficeLocation;
use App\Models\Unit;
use App\Models\User;

beforeEach(function () {
    // Admin
    $this->admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin.loc@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // PPPK
    $this->pppk = User::create([
        'name' => 'PPPK Test',
        'email' => 'pppk.loc@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->unit = Unit::create([
        'kode' => 'UNIT-LOC-1',
        'nama' => 'Kantor Pusat Disarpus Subang',
        'alamat' => 'Jl. MT Haryono No. 9',
        'status' => true,
    ]);

    $this->location = OfficeLocation::create([
        'unit_id' => $this->unit->id,
        'nama' => 'Gedung Utama Disarpus',
        'alamat' => 'Jl. MT Haryono No. 9 Subang',
        'latitude' => -6.5683000,
        'longitude' => 107.7634000,
        'radius_meter' => 100,
        'status' => true,
    ]);
});

test('guest tidak dapat mengakses manajemen lokasi kantor', function () {
    $response = $this->get(route('admin.office-locations.index'));
    $response->assertRedirect(route('login'));
});

test('pppk tidak dapat mengakses manajemen lokasi kantor', function () {
    $response = $this->actingAs($this->pppk)->get(route('admin.office-locations.index'));
    $response->assertStatus(403);
});

test('admin dapat melihat daftar lokasi kantor', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.office-locations.index'));
    $response->assertStatus(200);
    $response->assertSee('Gedung Utama Disarpus');
    $response->assertSee('-6.568300');
});

test('admin dapat membuka halaman form tambah lokasi kantor', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.office-locations.create'));
    $response->assertStatus(200);
    $response->assertSee('Tambah Lokasi Kantor Absensi');
});

test('admin dapat menambahkan lokasi kantor baru dengan data valid', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.office-locations.store'), [
        'unit_id' => $this->unit->id,
        'nama' => 'Depo Arsip Subang',
        'alamat' => 'Jl. Sukamelang No. 12 Subang',
        'latitude' => -6.5650000,
        'longitude' => 107.7600000,
        'radius_meter' => 150,
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.office-locations.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('office_locations', [
        'nama' => 'Depo Arsip Subang',
        'radius_meter' => 150,
        'status' => true,
    ]);
});

test('validasi pembuatan lokasi kantor menolak koordinat tidak valid atau radius minus', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.office-locations.store'), [
        'nama' => '',
        'latitude' => 150, // Invalid latitude (> 90)
        'longitude' => 'invalid',
        'radius_meter' => 5, // Kurang dari min:10
        'status' => '',
    ]);

    $response->assertSessionHasErrors(['nama', 'latitude', 'longitude', 'radius_meter', 'status']);
});

test('admin dapat mengedit data lokasi kantor dan mengubah radius', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.office-locations.update', $this->location), [
        'unit_id' => $this->unit->id,
        'nama' => 'Gedung Utama Disarpus (Update)',
        'alamat' => 'Jl. MT Haryono No. 9 Subang Baru',
        'latitude' => -6.5683500,
        'longitude' => 107.7634500,
        'radius_meter' => 120,
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.office-locations.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('office_locations', [
        'id' => $this->location->id,
        'nama' => 'Gedung Utama Disarpus (Update)',
        'radius_meter' => 120,
    ]);
});

test('admin dapat melakukan toggle status lokasi kantor', function () {
    expect($this->location->status)->toBeTrue();

    $response = $this->actingAs($this->admin)->patch(route('admin.office-locations.toggle-status', $this->location));
    $response->assertSessionHas('success');

    $this->location->refresh();
    expect((bool)$this->location->status)->toBeFalse();
});

test('admin dapat menghapus lokasi kantor', function () {
    $response = $this->actingAs($this->admin)->delete(route('admin.office-locations.destroy', $this->location));
    $response->assertRedirect(route('admin.office-locations.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('office_locations', ['id' => $this->location->id]);
});
