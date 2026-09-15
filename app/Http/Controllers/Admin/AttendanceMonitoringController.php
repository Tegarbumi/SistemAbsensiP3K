<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\OfficeLocation;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceMonitoringController extends Controller
{
    /**
     * Menampilkan data absensi harian PPPK dengan filter.
     */
    public function index(Request $request): View
    {
        $selectedDate = $request->input('tanggal', Carbon::today()->toDateString());

        $query = Attendance::with(['employee.unit', 'employee.user'])
            ->whereDate('tanggal', $selectedDate);

        // Filter nama / NIP
        if ($search = $request->input('search')) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nomor_pppk', 'like', "%{$search}%");
            });
        }

        // Filter unit
        if ($unitId = $request->input('unit_id')) {
            $query->whereHas('employee', function ($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            });
        }

        // Filter status kehadiran
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Ringkasan untuk tanggal terpilih
        $baseQuery = Attendance::whereDate('tanggal', $selectedDate);
        if ($unitId) {
            $baseQuery->whereHas('employee', function ($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            });
        }

        $summary = [
            'total' => (clone $baseQuery)->count(),
            'hadir' => (clone $baseQuery)->where('status', 'hadir')->count(),
            'terlambat' => (clone $baseQuery)->where('status', 'terlambat')->count(),
            'izin' => (clone $baseQuery)->where('status', 'izin')->count(),
            'sakit' => (clone $baseQuery)->where('status', 'sakit')->count(),
            'dinas' => (clone $baseQuery)->where('status', 'dinas')->count(),
            'cuti' => (clone $baseQuery)->where('status', 'cuti')->count(),
            'alpha' => (clone $baseQuery)->where('status', 'alpha')->count(),
        ];

        $attendances = $query->orderBy('jam_masuk', 'desc')->paginate(15)->withQueryString();
        $units = Unit::where('status', true)->orderBy('nama', 'asc')->get();

        $formattedDate = Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM Y');

        return view('admin.attendance-monitoring.index', [
            'attendances' => $attendances,
            'units' => $units,
            'selectedDate' => $selectedDate,
            'formattedDate' => $formattedDate,
            'summary' => $summary,
        ]);
    }

    /**
     * Menampilkan detail absensi pegawai (selfie, GPS, peta).
     */
    public function show(Attendance $attendance): View
    {
        $attendance->load(['employee.unit', 'employee.user', 'locations']);
        $employee = $attendance->employee;

        // Ambil lokasi kantor acuan
        $officeLocation = null;
        if ($employee && $employee->unit_id) {
            $officeLocation = OfficeLocation::where('unit_id', $employee->unit_id)
                ->where('status', true)
                ->first();
        }
        if (!$officeLocation) {
            $officeLocation = OfficeLocation::where('status', true)->first();
        }

        $carbonDate = Carbon::parse($attendance->tanggal);
        $tanggalFormatted = $carbonDate->locale('id')->isoFormat('dddd, D MMMM Y');
        $hari = $carbonDate->locale('id')->isoFormat('dddd');

        return view('admin.attendance-monitoring.show', [
            'attendance' => $attendance,
            'employee' => $employee,
            'officeLocation' => $officeLocation,
            'tanggalFormatted' => $tanggalFormatted,
            'hari' => $hari,
        ]);
    }
}
