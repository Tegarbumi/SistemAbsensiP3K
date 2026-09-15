<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\OfficeLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceHistoryController extends Controller
{
    /**
     * Menampilkan daftar riwayat absensi PPPK dengan filter dan pagination.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $employee = $user->employee;

        if (!$employee) {
            abort(404, 'Data pegawai tidak ditemukan.');
        }

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $selectedMonth = $request->input('bulan', $currentMonth);
        $selectedYear = $request->input('tahun', $currentYear);
        $selectedStatus = $request->input('status', 'all');

        $query = Attendance::where('employee_id', $employee->id);

        if ($selectedMonth && $selectedMonth !== 'all') {
            $query->whereMonth('tanggal', $selectedMonth);
        }

        if ($selectedYear && $selectedYear !== 'all') {
            $query->whereYear('tanggal', $selectedYear);
        }

        if ($selectedStatus && $selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }

        $attendances = $query->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Data untuk visualisasi kalender bulan yang dipilih (jika memilih bulan dan tahun tertentu)
        $calendarDays = [];
        if ($selectedMonth !== 'all' && $selectedYear !== 'all') {
            $monthDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
            $daysInMonth = $monthDate->daysInMonth;

            $monthAttendances = Attendance::where('employee_id', $employee->id)
                ->whereMonth('tanggal', $selectedMonth)
                ->whereYear('tanggal', $selectedYear)
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::parse($item->tanggal)->format('Y-m-d');
                });

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dayObj = Carbon::createFromDate($selectedYear, $selectedMonth, $d);
                $dateStr = $dayObj->format('Y-m-d');
                $att = $monthAttendances->get($dateStr);

                $calendarDays[] = [
                    'day' => $d,
                    'date' => $dateStr,
                    'dayName' => $dayObj->locale('id')->isoFormat('dd'),
                    'isWeekend' => $dayObj->isWeekend(),
                    'isToday' => $dayObj->isToday(),
                    'attendance' => $att,
                ];
            }
        }

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $daftarTahun = range($currentYear - 3, $currentYear + 1);

        return view('employee.history.index', [
            'employee' => $employee,
            'attendances' => $attendances,
            'calendarDays' => $calendarDays,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'selectedStatus' => $selectedStatus,
            'daftarBulan' => $daftarBulan,
            'daftarTahun' => $daftarTahun,
        ]);
    }

    /**
     * Menampilkan detail satu rekaman absensi dengan foto selfie dan peta Leaflet.
     */
    public function show(Request $request, Attendance $attendance): View
    {
        $user = $request->user();
        $employee = $user->employee;

        // Validasi otorisasi: PPPK hanya dapat melihat data miliknya sendiri
        if (!$employee || $attendance->employee_id !== $employee->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat data absensi ini.');
        }

        // Cari lokasi kantor
        $officeLocation = null;
        if ($employee->unit_id) {
            $officeLocation = OfficeLocation::where('unit_id', $employee->unit_id)
                ->where('status', true)
                ->first();
        }

        if (!$officeLocation) {
            $officeLocation = OfficeLocation::where('status', true)->first();
        }

        $dateCarbon = Carbon::parse($attendance->tanggal);
        $hariIndonesia = $this->namaHariIndonesia($dateCarbon);
        $tanggalFormatted = $hariIndonesia . ', ' . $dateCarbon->format('d') . ' ' . $this->namaBulanIndonesia($dateCarbon) . ' ' . $dateCarbon->format('Y');

        return view('employee.history.show', [
            'employee' => $employee,
            'attendance' => $attendance,
            'officeLocation' => $officeLocation,
            'hari' => $hariIndonesia,
            'tanggalFormatted' => $tanggalFormatted,
        ]);
    }

    private function namaHariIndonesia(Carbon $date): string
    {
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return $hari[$date->format('l')] ?? $date->format('l');
    }

    private function namaBulanIndonesia(Carbon $date): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $bulan[(int) $date->format('n')] ?? $date->format('F');
    }
}
