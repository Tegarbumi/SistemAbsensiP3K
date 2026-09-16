<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');

    // Admin
    $this->admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin.leave@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // PPPK 1
    $this->pppk = User::create([
        'name' => 'PPPK Test',
        'email' => 'pppk.leave@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    // PPPK 2
    $this->pppkOther = User::create([
        'name' => 'PPPK Other',
        'email' => 'pppk.other@subang.go.id',
        'password' => bcrypt('password'),
        'role' => 'pppk',
    ]);

    $this->unit = Unit::create([
        'kode' => 'UNIT-LEAVE',
        'nama' => 'Bidang Kearsipan',
        'status' => true,
    ]);

    $this->employee = Employee::create([
        'user_id' => $this->pppk->id,
        'unit_id' => $this->unit->id,
        'nip' => '199101012024211001',
        'nama' => 'Ahmad Subang',
        'jabatan' => 'Arsiparis',
        'status' => true,
    ]);

    $this->employeeOther = Employee::create([
        'user_id' => $this->pppkOther->id,
        'unit_id' => $this->unit->id,
        'nip' => '199102022024211002',
        'nama' => 'Siti Subang',
        'jabatan' => 'Pustakawan',
        'status' => true,
    ]);
});

test('guest tidak dapat mengakses pengajuan izin', function () {
    $response = $this->get(route('leave-requests.index'));
    $response->assertRedirect(route('login'));
});

test('pppk tidak dapat mengakses persetujuan izin admin', function () {
    $response = $this->actingAs($this->pppk)->get(route('admin.leave-requests.index'));
    $response->assertStatus(403);
});

test('pppk dapat melihat daftar pengajuan izin miliknya', function () {
    $leave = LeaveRequest::create([
        'employee_id' => $this->employee->id,
        'jenis' => 'sakit',
        'tanggal_mulai' => '2026-09-20',
        'tanggal_selesai' => '2026-09-21',
        'alasan' => 'Demam dan flu berat',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($this->pppk)->get(route('leave-requests.index'));
    $response->assertStatus(200);
    $response->assertSee('Demam dan flu berat');
});

test('pppk dapat mengajukan izin baru dengan upload dokumen', function () {
    $file = UploadedFile::fake()->create('surat_dokter.pdf', 100, 'application/pdf');

    $response = $this->actingAs($this->pppk)->post(route('leave-requests.store'), [
        'jenis' => 'sakit',
        'tanggal_mulai' => '2026-09-22',
        'tanggal_selesai' => '2026-09-23',
        'alasan' => 'Rawat inap di RSUD Subang',
        'dokumen' => $file,
    ]);

    $response->assertRedirect(route('leave-requests.index'));
    $response->assertSessionHas('success');

    $latest = LeaveRequest::where('employee_id', $this->employee->id)->latest()->first();
    expect($latest)->not->toBeNull();
    expect($latest->jenis)->toBe('sakit');
    expect($latest->tanggal_mulai->toDateString())->toBe('2026-09-22');
    expect($latest->tanggal_selesai->toDateString())->toBe('2026-09-23');
    expect($latest->status)->toBe('menunggu');
});

test('validasi pengajuan menolak tanggal selesai mendahului tanggal mulai', function () {
    $response = $this->actingAs($this->pppk)->post(route('leave-requests.store'), [
        'jenis' => 'cuti',
        'tanggal_mulai' => '2026-09-25',
        'tanggal_selesai' => '2026-09-20',
        'alasan' => 'Cuti tahunan',
    ]);

    $response->assertSessionHasErrors(['tanggal_selesai']);
});

test('pppk tidak dapat melihat detail pengajuan milik pppk lain', function () {
    $leaveOther = LeaveRequest::create([
        'employee_id' => $this->employeeOther->id,
        'jenis' => 'izin',
        'tanggal_mulai' => '2026-09-20',
        'tanggal_selesai' => '2026-09-20',
        'alasan' => 'Keperluan keluarga penting',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($this->pppk)->get(route('leave-requests.show', $leaveOther));
    $response->assertStatus(403);
});

test('admin dapat melihat daftar seluruh pengajuan izin', function () {
    LeaveRequest::create([
        'employee_id' => $this->employee->id,
        'jenis' => 'cuti',
        'tanggal_mulai' => '2026-09-20',
        'tanggal_selesai' => '2026-09-22',
        'alasan' => 'Cuti tahunan',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.leave-requests.index'));
    $response->assertStatus(200);
    $response->assertSee('Ahmad Subang');
    $response->assertSee('Cuti tahunan');
});

test('admin dapat menyetujui permohonan dan otomatis mencatat kehadiran di attendances', function () {
    $leave = LeaveRequest::create([
        'employee_id' => $this->employee->id,
        'jenis' => 'izin',
        'tanggal_mulai' => '2026-09-20',
        'tanggal_selesai' => '2026-09-21',
        'alasan' => 'Mengurus dokumen dinas di Bandung',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($this->admin)->patch(route('admin.leave-requests.approve', $leave));
    $response->assertSessionHas('success');

    $leave->refresh();
    expect($leave->status)->toBe('disetujui');
    expect($leave->approved_by)->toBe($this->admin->id);

    // Cek record kehadiran otomatis untuk 2026-09-20 dan 2026-09-21
    $att1 = Attendance::where('employee_id', $this->employee->id)->whereDate('tanggal', '2026-09-20')->first();
    expect($att1)->not->toBeNull();
    expect($att1->status)->toBe('izin');

    $att2 = Attendance::where('employee_id', $this->employee->id)->whereDate('tanggal', '2026-09-21')->first();
    expect($att2)->not->toBeNull();
    expect($att2->status)->toBe('izin');
});

test('admin dapat menolak permohonan izin', function () {
    $leave = LeaveRequest::create([
        'employee_id' => $this->employee->id,
        'jenis' => 'cuti',
        'tanggal_mulai' => '2026-09-25',
        'tanggal_selesai' => '2026-09-26',
        'alasan' => 'Cuti mendadak',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($this->admin)->patch(route('admin.leave-requests.reject', $leave));
    $response->assertSessionHas('success');

    $leave->refresh();
    expect($leave->status)->toBe('ditolak');
});
