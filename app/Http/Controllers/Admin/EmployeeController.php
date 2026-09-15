<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar pegawai PPPK dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $query = Employee::with(['user', 'unit']);

        // Filter pencarian (nama / NIP / nomor PPPK)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nomor_pppk', 'like', "%{$search}%");
            });
        }

        // Filter unit
        if ($unitId = $request->input('unit_id')) {
            $query->where('unit_id', $unitId);
        }

        // Filter status (aktif / nonaktif)
        if ($request->has('status') && $request->input('status') !== 'all' && $request->input('status') !== null) {
            $statusBool = $request->input('status') === '1' || $request->input('status') === 'aktif';
            $query->where('status', $statusBool);
        }

        $employees = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();
        $units = Unit::where('status', true)->orderBy('nama', 'asc')->get();

        return view('admin.employees.index', [
            'employees' => $employees,
            'units' => $units,
        ]);
    }

    /**
     * Menampilkan form tambah pegawai PPPK baru.
     */
    public function create(): View
    {
        $units = Unit::where('status', true)->orderBy('nama', 'asc')->get();

        return view('admin.employees.create', [
            'units' => $units,
        ]);
    }

    /**
     * Menyimpan data pegawai PPPK baru (sekaligus membuat user akun PPPK).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:employees,nip',
            'nomor_pppk' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|max:2048',
        ], [
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat User dengan role PPPK
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password ?: 'password'),
                'role' => 'pppk',
                'email_verified_at' => now(),
            ]);

            // 2. Upload foto profil jika ada
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('employees', 'public');
            }

            // 3. Buat Employee terhubung ke user
            Employee::create([
                'user_id' => $user->id,
                'unit_id' => $request->unit_id,
                'nip' => $request->nip,
                'nomor_pppk' => $request->nomor_pppk,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'foto' => $fotoPath,
                'status' => true,
            ]);
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Pegawai PPPK baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail profil dan riwayat presensi pegawai.
     */
    public function show(Employee $employee): View
    {
        $employee->load(['user', 'unit']);
        $recentAttendances = $employee->attendances()
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        return view('admin.employees.show', [
            'employee' => $employee,
            'recentAttendances' => $recentAttendances,
        ]);
    }

    /**
     * Menampilkan form edit data pegawai PPPK.
     */
    public function edit(Employee $employee): View
    {
        $employee->load(['user', 'unit']);
        $units = Unit::where('status', true)->orderBy('nama', 'asc')->get();

        return view('admin.employees.edit', [
            'employee' => $employee,
            'units' => $units,
        ]);
    }

    /**
     * Memperbarui data pegawai PPPK dan akun terkait.
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:employees,nip,' . $employee->id,
            'nomor_pppk' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->user_id,
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ], [
            'nip.unique' => 'NIP sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
        ]);

        DB::transaction(function () use ($request, $employee) {
            // Update data user
            $user = $employee->user;
            if ($user) {
                $userData = [
                    'name' => $request->nama,
                    'email' => $request->email,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $user->update($userData);
            }

            // Upload foto baru jika ada
            $fotoPath = $employee->foto;
            if ($request->hasFile('foto')) {
                if ($employee->foto && Storage::disk('public')->exists($employee->foto)) {
                    Storage::disk('public')->delete($employee->foto);
                }
                $fotoPath = $request->file('foto')->store('employees', 'public');
            }

            // Update data employee
            $employee->update([
                'unit_id' => $request->unit_id,
                'nip' => $request->nip,
                'nomor_pppk' => $request->nomor_pppk,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'foto' => $fotoPath,
                'status' => (bool)$request->status,
            ]);
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data pegawai PPPK berhasil diperbarui.');
    }

    /**
     * Mengubah status aktif / nonaktif pegawai.
     */
    public function toggleStatus(Employee $employee): RedirectResponse
    {
        $employee->status = !$employee->status;
        $employee->save();

        $statusText = $employee->status ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status pegawai {$employee->nama} berhasil {$statusText}.");
    }

    /**
     * Menghapus pegawai dengan proteksi histori absensi.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        // Proteksi: jangan izinkan menghapus pegawai yang memiliki histori absensi atau cuti
        if ($employee->attendances()->exists() || $employee->leaveRequests()->exists()) {
            return redirect()->back()->with('error', 'Pegawai tidak dapat dihapus karena memiliki riwayat absensi atau permohonan izin/cuti. Silakan gunakan opsi Nonaktifkan Status.');
        }

        DB::transaction(function () use ($employee) {
            $user = $employee->user;

            if ($employee->foto && Storage::disk('public')->exists($employee->foto)) {
                Storage::disk('public')->delete($employee->foto);
            }

            $employee->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
