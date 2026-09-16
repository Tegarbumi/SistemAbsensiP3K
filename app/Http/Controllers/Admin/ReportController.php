<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) $request->input('month', now()->month);
        $selectedYear = (int) $request->input('year', now()->year);
        $selectedUnitId = $request->input('unit_id');
        $selectedEmployeeId = $request->input('employee_id');

        $units = Unit::orderBy('nama')->get();
        $employees = Employee::where('status', true)->orderBy('nama')->get();

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('admin.reports.index', compact(
            'units',
            'employees',
            'selectedMonth',
            'selectedYear',
            'selectedUnitId',
            'selectedEmployeeId',
            'months'
        ));
    }

    protected function getReportData(Request $request): array
    {
        $selectedMonth = (int) $request->input('month', now()->month);
        $selectedYear = (int) $request->input('year', now()->year);
        $selectedUnitId = $request->input('unit_id');
        $selectedEmployeeId = $request->input('employee_id');

        $employeeQuery = Employee::with(['unit'])->where('status', true);

        if ($selectedUnitId) {
            $employeeQuery->where('unit_id', $selectedUnitId);
        }

        if ($selectedEmployeeId) {
            $employeeQuery->where('id', $selectedEmployeeId);
        }

        $employees = $employeeQuery->orderBy('nama')->get();

        $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();
        $period = CarbonPeriod::create($startDate, $endDate);

        $workingDays = 0;
        foreach ($period as $date) {
            if ($date->isWeekday()) {
                $workingDays++;
            }
        }

        $attendances = Attendance::whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->whereIn('employee_id', $employees->pluck('id'))
            ->get()
            ->groupBy('employee_id');

        $recap = $employees->map(function ($emp) use ($attendances, $workingDays) {
            $empAttendances = $attendances->get($emp->id, collect());

            $hadir = $empAttendances->where('status', 'hadir')->count();
            $terlambat = $empAttendances->where('status', 'terlambat')->count();
            $izin = $empAttendances->where('status', 'izin')->count();
            $sakit = $empAttendances->where('status', 'sakit')->count();
            $cuti = $empAttendances->where('status', 'cuti')->count();
            $dinas = $empAttendances->filter(fn($a) => in_array($a->status, ['dinas', 'dinas_luar']))->count();

            $recordedDays = $hadir + $terlambat + $izin + $sakit + $cuti + $dinas;
            $alpha = max(0, $workingDays - $recordedDays);
            $rate = $workingDays > 0 ? round((($hadir + $terlambat) / $workingDays) * 100) : 0;

            return [
                'nama' => $emp->nama,
                'nip' => $emp->nip ?? '-',
                'nomor_pppk' => $emp->nomor_pppk ?? '-',
                'unit' => $emp->unit->nama ?? '-',
                'jabatan' => $emp->jabatan ?? '-',
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'cuti' => $cuti,
                'dinas' => $dinas,
                'alpha' => $alpha,
                'rate' => $rate . '%',
            ];
        });

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return [
            'recap' => $recap,
            'month' => $selectedMonth,
            'year' => $selectedYear,
            'monthName' => $monthNames[$selectedMonth],
            'workingDays' => $workingDays,
            'unitName' => $selectedUnitId ? (Unit::find($selectedUnitId)?->nama ?? 'Semua Unit') : 'Semua Unit',
        ];
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Laporan_Absensi_PPPK_' . $data['monthName'] . '_' . $data['year'] . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $data = $this->getReportData($request);
        $filename = 'Laporan_Absensi_PPPK_' . $data['monthName'] . '_' . $data['year'] . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($data) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($output, ['LAPORAN REKAPITULASI ABSENSI PPPK']);
            fputcsv($output, ['DINAS KEARSIPAN DAN PERPUSTAKAAN KABUPATEN SUBANG']);
            fputcsv($output, ['Periode: ' . $data['monthName'] . ' ' . $data['year']]);
            fputcsv($output, ['Unit Kerja: ' . $data['unitName']]);
            fputcsv($output, ['Hari Kerja Efektif: ' . $data['workingDays'] . ' Hari']);
            fputcsv($output, []);

            fputcsv($output, [
                'No', 'Nama Pegawai', 'NIP', 'Nomor PPPK', 'Unit Kerja', 'Jabatan',
                'Hadir', 'Terlambat', 'Izin', 'Sakit', 'Cuti', 'Dinas Luar', 'Alpha', 'Persentase Kehadiran'
            ]);

            foreach ($data['recap'] as $i => $row) {
                fputcsv($output, [
                    $i + 1,
                    $row['nama'],
                    "'".$row['nip'],
                    "'".$row['nomor_pppk'],
                    $row['unit'],
                    $row['jabatan'],
                    $row['hadir'],
                    $row['terlambat'],
                    $row['izin'],
                    $row['sakit'],
                    $row['cuti'],
                    $row['dinas'],
                    $row['alpha'],
                    $row['rate'],
                ]);
            }

            fclose($output);
        }, 200, $headers);
    }
}
