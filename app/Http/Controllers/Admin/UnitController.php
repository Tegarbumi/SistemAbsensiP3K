<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitController extends Controller
{
    /**
     * Menampilkan daftar unit/bagian dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request): View
    {
        $query = Unit::withCount(['employees', 'officeLocations']);

        // Filter pencarian (kode / nama)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        // Filter status (aktif / nonaktif)
        if ($request->has('status') && $request->input('status') !== 'all' && $request->input('status') !== null) {
            $statusBool = $request->input('status') === '1' || $request->input('status') === 'aktif';
            $query->where('status', $statusBool);
        }

        $units = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('admin.units.index', [
            'units' => $units,
        ]);
    }

    /**
     * Menampilkan form tambah unit/bagian baru.
     */
    public function create(): View
    {
        return view('admin.units.create');
    }

    /**
     * Menyimpan unit/bagian baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:units,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ], [
            'kode.required' => 'Kode unit wajib diisi.',
            'kode.unique' => 'Kode unit sudah digunakan.',
            'nama.required' => 'Nama unit wajib diisi.',
            'status.required' => 'Status unit wajib dipilih.',
        ]);

        Unit::create([
            'kode' => strtoupper(trim($validated['kode'])),
            'nama' => trim($validated['nama']),
            'alamat' => $validated['alamat'] ? trim($validated['alamat']) : null,
            'status' => (bool)$validated['status'],
        ]);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit unit/bagian.
     */
    public function edit(Unit $unit): View
    {
        return view('admin.units.edit', [
            'unit' => $unit,
        ]);
    }

    /**
     * Memperbarui data unit/bagian.
     */
    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', Rule::unique('units', 'kode')->ignore($unit->id)],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ], [
            'kode.required' => 'Kode unit wajib diisi.',
            'kode.unique' => 'Kode unit sudah digunakan.',
            'nama.required' => 'Nama unit wajib diisi.',
            'status.required' => 'Status unit wajib dipilih.',
        ]);

        $unit->update([
            'kode' => strtoupper(trim($validated['kode'])),
            'nama' => trim($validated['nama']),
            'alamat' => $validated['alamat'] ? trim($validated['alamat']) : null,
            'status' => (bool)$validated['status'],
        ]);

        return redirect()->route('admin.units.index')
            ->with('success', 'Data unit kerja berhasil diperbarui.');
    }

    /**
     * Mengubah status aktif / nonaktif unit.
     */
    public function toggleStatus(Unit $unit): RedirectResponse
    {
        $unit->status = !$unit->status;
        $unit->save();

        $statusText = $unit->status ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status unit {$unit->nama} berhasil {$statusText}.");
    }

    /**
     * Menghapus unit dengan proteksi data terkait.
     */
    public function destroy(Unit $unit): RedirectResponse
    {
        if ($unit->employees()->exists() || $unit->officeLocations()->exists()) {
            return redirect()->back()
                ->with('error', 'Unit tidak dapat dihapus karena masih memiliki data pegawai atau lokasi kantor terkait. Silakan nonaktifkan status unit ini.');
        }

        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit kerja berhasil dihapus.');
    }
}
