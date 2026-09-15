<?php

use App\Models\User;
use App\Models\WorkSchedule;

beforeEach(function () {
    // Admin
    $this->admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin.sched@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // PPPK
    $this->pppk = User::create([
        'name' => 'PPPK Test',
        'email' => 'pppk.sched@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->schedule = WorkSchedule::create([
        'nama' => 'Jadwal Reguler Disarpus',
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
        'status' => true,
    ]);
});

test('guest tidak dapat mengakses manajemen jadwal kerja', function () {
    $response = $this->get(route('admin.work-schedules.index'));
    $response->assertRedirect(route('login'));
});

test('pppk tidak dapat mengakses manajemen jadwal kerja admin', function () {
    $response = $this->actingAs($this->pppk)->get(route('admin.work-schedules.index'));
    $response->assertStatus(403);
});

test('admin dapat melihat daftar jadwal kerja', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.work-schedules.index'));
    $response->assertStatus(200);
    $response->assertSee('Jadwal Reguler Disarpus');
    $response->assertSee('07:30');
    $response->assertSee('16:00');
});

test('admin dapat membuka halaman form tambah jadwal kerja', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.work-schedules.create'));
    $response->assertStatus(200);
    $response->assertSee('Tambah Jadwal Kerja');
});

test('admin dapat menambahkan jadwal kerja baru dengan data valid', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.work-schedules.store'), [
        'nama' => 'Jadwal Khusus Layanan Perpustakaan',
        'jam_masuk' => '08:00',
        'jam_pulang' => '15:30',
        'toleransi_terlambat' => 10,
        'senin' => '1',
        'selasa' => '1',
        'rabu' => '1',
        'kamis' => '1',
        'jumat' => '1',
        'sabtu' => '1',
        'minggu' => '0',
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.work-schedules.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('work_schedules', [
        'nama' => 'Jadwal Khusus Layanan Perpustakaan',
        'jam_masuk' => '08:00:00',
        'jam_pulang' => '15:30:00',
        'toleransi_terlambat' => 10,
        'sabtu' => true,
        'status' => true,
    ]);
});

test('validasi penambahan jadwal kerja menolak format jam salah atau kolom kosong', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.work-schedules.store'), [
        'nama' => '',
        'jam_masuk' => '25:00',
        'jam_pulang' => 'invalid',
        'toleransi_terlambat' => 'abc',
        'status' => '',
    ]);

    $response->assertSessionHasErrors(['nama', 'jam_masuk', 'jam_pulang', 'toleransi_terlambat', 'status']);
});

test('admin dapat mengedit data jadwal kerja', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.work-schedules.update', $this->schedule), [
        'nama' => 'Jadwal Reguler Disarpus (Revisi)',
        'jam_masuk' => '07:15',
        'jam_pulang' => '16:15',
        'toleransi_terlambat' => 20,
        'senin' => '1',
        'selasa' => '1',
        'rabu' => '1',
        'kamis' => '1',
        'jumat' => '1',
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.work-schedules.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('work_schedules', [
        'id' => $this->schedule->id,
        'nama' => 'Jadwal Reguler Disarpus (Revisi)',
        'jam_masuk' => '07:15:00',
        'jam_pulang' => '16:15:00',
        'toleransi_terlambat' => 20,
    ]);
});

test('admin dapat melakukan toggle status jadwal kerja', function () {
    expect($this->schedule->status)->toBeTrue();

    $response = $this->actingAs($this->admin)->patch(route('admin.work-schedules.toggle-status', $this->schedule));
    $response->assertSessionHas('success');

    $this->schedule->refresh();
    expect((bool)$this->schedule->status)->toBeFalse();
});

test('admin dapat menghapus jadwal kerja', function () {
    $response = $this->actingAs($this->admin)->delete(route('admin.work-schedules.destroy', $this->schedule));
    $response->assertRedirect(route('admin.work-schedules.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('work_schedules', ['id' => $this->schedule->id]);
});
