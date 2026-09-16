<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\OfficeLocation;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GpsMonitoringController extends Controller
{
    /**
     * Menampilkan peta monitoring GPS presensi PPPK secara visual (Leaflet + OpenStreetMap).
     */
    public function index(Request $request): View
    {
        $selectedDate = $request->input('tanggal', Carbon::today()->toDateString());
        $unitId = $request->input('unit_id');

        // Ambil lokasi kantor aktif
        $officeLocationsQuery = OfficeLocation::with('unit')->where('status', true);
        if ($unitId) {
            $officeLocationsQuery->where(function ($q) use ($unitId) {
                $q->where('unit_id', $unitId)->orWhereNull('unit_id');
            });
        }
        $officeLocations = $officeLocationsQuery->get();

        // Ambil data absensi pada tanggal terpilih
        $attendanceQuery = Attendance::with(['employee.unit'])
            ->whereDate('tanggal', $selectedDate)
            ->where(function ($q) {
                $q->whereNotNull('latitude_masuk')
                  ->orWhereNotNull('latitude_pulang');
            });

        if ($unitId) {
            $attendanceQuery->whereHas('employee', function ($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            });
        }

        $attendances = $attendanceQuery->get();

        // Siapkan marker points untuk peta
        $markers = [];
        foreach ($attendances as $att) {
            if ($att->latitude_masuk && $att->longitude_masuk) {
                $markers[] = [
                    'type' => 'masuk',
                    'attendance_id' => $att->id,
                    'nama' => $att->employee?->nama ?: 'Pegawai',
                    'nip' => $att->employee?->nip ?: '-',
                    'unit' => $att->employee?->unit?->nama ?: '-',
                    'waktu' => $att->jam_masuk,
                    'status' => $att->status,
                    'lat' => (float)$att->latitude_masuk,
                    'lng' => (float)$att->longitude_masuk,
                    'jarak' => round($att->distance_masuk ?? 0),
                    'selfie' => $att->selfie_masuk ? asset('storage/' . $att->selfie_masuk) : null,
                ];
            }

            if ($att->latitude_pulang && $att->longitude_pulang) {
                $markers[] = [
                    'type' => 'pulang',
                    'attendance_id' => $att->id,
                    'nama' => $att->employee?->nama ?: 'Pegawai',
                    'nip' => $att->employee?->nip ?: '-',
                    'unit' => $att->employee?->unit?->nama ?: '-',
                    'waktu' => $att->jam_pulang,
                    'status' => $att->status,
                    'lat' => (float)$att->latitude_pulang,
                    'lng' => (float)$att->longitude_pulang,
                    'jarak' => round($att->distance_pulang ?? 0),
                    'selfie' => $att->selfie_pulang ? asset('storage/' . $att->selfie_pulang) : null,
                ];
            }
        }

        $units = Unit::where('status', true)->orderBy('nama', 'asc')->get();
        $formattedDate = Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM Y');

        return view('admin.gps-monitoring.index', [
            'officeLocations' => $officeLocations,
            'markers' => $markers,
            'attendances' => $attendances,
            'units' => $units,
            'selectedDate' => $selectedDate,
            'formattedDate' => $formattedDate,
        ]);
    }
}
