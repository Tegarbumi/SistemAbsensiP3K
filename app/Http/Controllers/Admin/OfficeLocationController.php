<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeLocation;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfficeLocationController extends Controller
{
    /**
     * Menampilkan daftar lokasi kantor absensi.
     */
    public function index(Request $request): View
    {
        $query = OfficeLocation::with('unit');

        // Pencarian nama kantor / alamat
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter unit
        if ($unitId = $request->input('unit_id')) {
            $query->where('unit_id', $unitId);
        }

        // Filter status
        if ($request->has('status') && $request->input('status') !== 'all' && $request->input('status') !== null) {
            $statusBool = $request->input('status') === '1' || $request->input('status') === 'aktif';
            $query->where('status', $statusBool);
        }

        $locations = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();
        $units = Unit::where('status', true)->orderBy('nama', 'asc')->get();

        return view('admin.office-locations.index', [
            'locations' => $locations,
            'units' => $units,
        ]);
    }

    /**
     * Menampilkan form tambah lokasi kantor baru.
     */
    public function create(): View
    {
        $units = Unit::where('status', true)->orderBy('nama', 'asc')->get();

        // Default koordinat Kantor Dinas Kearsipan dan Perpustakaan Subang
        $defaultCoords = [
            'lat' => -6.5683,
            'lng' => 107.7634,
            'radius' => 100,
        ];

        return view('admin.office-locations.create', [
            'units' => $units,
            'defaultCoords' => $defaultCoords,
        ]);
    }

    /**
     * Menyimpan lokasi kantor baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unit_id' => ['nullable', 'exists:units,id'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:10', 'max:5000'],
            'status' => ['required', 'in:0,1'],
        ], [
            'nama.required' => 'Nama lokasi kantor wajib diisi.',
            'latitude.required' => 'Koordinat latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka desimal.',
            'longitude.required' => 'Koordinat longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka desimal.',
            'radius_meter.required' => 'Radius absensi wajib diisi.',
            'radius_meter.min' => 'Radius minimal adalah 10 meter.',
            'radius_meter.max' => 'Radius maksimal adalah 5000 meter.',
            'status.required' => 'Status operasional wajib dipilih.',
        ]);

        OfficeLocation::create([
            'unit_id' => $validated['unit_id'] ?: null,
            'nama' => trim($validated['nama']),
            'alamat' => $validated['alamat'] ? trim($validated['alamat']) : null,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'radius_meter' => $validated['radius_meter'],
            'status' => (bool)$validated['status'],
        ]);

        return redirect()->route('admin.office-locations.index')
            ->with('success', 'Lokasi kantor absensi berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit lokasi kantor.
     */
    public function edit(OfficeLocation $lokasiKantor): View
    {
        $units = Unit::where('status', true)->orderBy('nama', 'asc')->get();

        return view('admin.office-locations.edit', [
            'location' => $lokasiKantor,
            'units' => $units,
        ]);
    }

    /**
     * Memperbarui data lokasi kantor.
     */
    public function update(Request $request, OfficeLocation $lokasiKantor): RedirectResponse
    {
        $validated = $request->validate([
            'unit_id' => ['nullable', 'exists:units,id'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:10', 'max:5000'],
            'status' => ['required', 'in:0,1'],
        ], [
            'nama.required' => 'Nama lokasi kantor wajib diisi.',
            'latitude.required' => 'Koordinat latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka desimal.',
            'longitude.required' => 'Koordinat longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka desimal.',
            'radius_meter.required' => 'Radius absensi wajib diisi.',
            'radius_meter.min' => 'Radius minimal adalah 10 meter.',
            'radius_meter.max' => 'Radius maksimal adalah 5000 meter.',
            'status.required' => 'Status operasional wajib dipilih.',
        ]);

        $lokasiKantor->update([
            'unit_id' => $validated['unit_id'] ?: null,
            'nama' => trim($validated['nama']),
            'alamat' => $validated['alamat'] ? trim($validated['alamat']) : null,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'radius_meter' => $validated['radius_meter'],
            'status' => (bool)$validated['status'],
        ]);

        return redirect()->route('admin.office-locations.index')
            ->with('success', 'Data lokasi kantor berhasil diperbarui.');
    }

    /**
     * Mengubah status aktif / nonaktif lokasi kantor.
     */
    public function toggleStatus(OfficeLocation $lokasiKantor): RedirectResponse
    {
        $lokasiKantor->status = !$lokasiKantor->status;
        $lokasiKantor->save();

        $statusText = $lokasiKantor->status ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status lokasi kantor {$lokasiKantor->nama} berhasil {$statusText}.");
    }

    /**
     * Menghapus lokasi kantor.
     */
    public function destroy(OfficeLocation $lokasiKantor): RedirectResponse
    {
        $lokasiKantor->delete();

        return redirect()->route('admin.office-locations.index')
            ->with('success', 'Lokasi kantor berhasil dihapus.');
    }
}
