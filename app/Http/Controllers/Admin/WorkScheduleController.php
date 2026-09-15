<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal kerja.
     */
    public function index(Request $request): View
    {
        $query = WorkSchedule::query();

        if ($search = $request->input('search')) {
            $query->where('nama', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->input('status') !== 'all' && $request->input('status') !== null) {
            $statusBool = $request->input('status') === '1' || $request->input('status') === 'aktif';
            $query->where('status', $statusBool);
        }

        $schedules = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('admin.work-schedules.index', [
            'schedules' => $schedules,
        ]);
    }

    /**
     * Menampilkan form tambah jadwal kerja baru.
     */
    public function create(): View
    {
        return view('admin.work-schedules.create');
    }

    /**
     * Menyimpan jadwal kerja baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jam_masuk' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i'],
            'toleransi_terlambat' => ['required', 'integer', 'min:0', 'max:120'],
            'senin' => ['nullable', 'boolean'],
            'selasa' => ['nullable', 'boolean'],
            'rabu' => ['nullable', 'boolean'],
            'kamis' => ['nullable', 'boolean'],
            'jumat' => ['nullable', 'boolean'],
            'sabtu' => ['nullable', 'boolean'],
            'minggu' => ['nullable', 'boolean'],
            'status' => ['required', 'in:0,1'],
        ], [
            'nama.required' => 'Nama jadwal kerja wajib diisi.',
            'jam_masuk.required' => 'Jam masuk wajib diisi.',
            'jam_masuk.date_format' => 'Format jam masuk harus JJ:MM (contoh: 07:30).',
            'jam_pulang.required' => 'Jam pulang wajib diisi.',
            'jam_pulang.date_format' => 'Format jam pulang harus JJ:MM (contoh: 16:00).',
            'toleransi_terlambat.required' => 'Toleransi keterlambatan wajib diisi.',
            'toleransi_terlambat.integer' => 'Toleransi harus berupa angka menit.',
            'status.required' => 'Status jadwal wajib dipilih.',
        ]);

        WorkSchedule::create([
            'nama' => trim($validated['nama']),
            'jam_masuk' => $validated['jam_masuk'] . ':00',
            'jam_pulang' => $validated['jam_pulang'] . ':00',
            'toleransi_terlambat' => $validated['toleransi_terlambat'],
            'senin' => $request->boolean('senin'),
            'selasa' => $request->boolean('selasa'),
            'rabu' => $request->boolean('rabu'),
            'kamis' => $request->boolean('kamis'),
            'jumat' => $request->boolean('jumat'),
            'sabtu' => $request->boolean('sabtu'),
            'minggu' => $request->boolean('minggu'),
            'status' => (bool)$validated['status'],
        ]);

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit jadwal kerja.
     */
    public function edit(WorkSchedule $jadwalKerja): View
    {
        return view('admin.work-schedules.edit', [
            'schedule' => $jadwalKerja,
        ]);
    }

    /**
     * Memperbarui jadwal kerja.
     */
    public function update(Request $request, WorkSchedule $jadwalKerja): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jam_masuk' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i'],
            'toleransi_terlambat' => ['required', 'integer', 'min:0', 'max:120'],
            'senin' => ['nullable', 'boolean'],
            'selasa' => ['nullable', 'boolean'],
            'rabu' => ['nullable', 'boolean'],
            'kamis' => ['nullable', 'boolean'],
            'jumat' => ['nullable', 'boolean'],
            'sabtu' => ['nullable', 'boolean'],
            'minggu' => ['nullable', 'boolean'],
            'status' => ['required', 'in:0,1'],
        ], [
            'nama.required' => 'Nama jadwal kerja wajib diisi.',
            'jam_masuk.required' => 'Jam masuk wajib diisi.',
            'jam_masuk.date_format' => 'Format jam masuk harus JJ:MM (contoh: 07:30).',
            'jam_pulang.required' => 'Jam pulang wajib diisi.',
            'jam_pulang.date_format' => 'Format jam pulang harus JJ:MM (contoh: 16:00).',
            'toleransi_terlambat.required' => 'Toleransi keterlambatan wajib diisi.',
            'toleransi_terlambat.integer' => 'Toleransi harus berupa angka menit.',
            'status.required' => 'Status jadwal wajib dipilih.',
        ]);

        $jadwalKerja->update([
            'nama' => trim($validated['nama']),
            'jam_masuk' => $validated['jam_masuk'] . ':00',
            'jam_pulang' => $validated['jam_pulang'] . ':00',
            'toleransi_terlambat' => $validated['toleransi_terlambat'],
            'senin' => $request->boolean('senin'),
            'selasa' => $request->boolean('selasa'),
            'rabu' => $request->boolean('rabu'),
            'kamis' => $request->boolean('kamis'),
            'jumat' => $request->boolean('jumat'),
            'sabtu' => $request->boolean('sabtu'),
            'minggu' => $request->boolean('minggu'),
            'status' => (bool)$validated['status'],
        ]);

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil diperbarui.');
    }

    /**
     * Mengubah status aktif / nonaktif jadwal kerja.
     */
    public function toggleStatus(WorkSchedule $jadwalKerja): RedirectResponse
    {
        $jadwalKerja->status = !$jadwalKerja->status;
        $jadwalKerja->save();

        $statusText = $jadwalKerja->status ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status jadwal kerja {$jadwalKerja->nama} berhasil {$statusText}.");
    }

    /**
     * Menghapus jadwal kerja.
     */
    public function destroy(WorkSchedule $jadwalKerja): RedirectResponse
    {
        $jadwalKerja->delete();

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil dihapus.');
    }
}
