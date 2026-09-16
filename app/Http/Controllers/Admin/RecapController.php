<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Unit;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) $request->input('month', now()->month);
        $selectedYear = (int) $request->input('year', now()->year);
        $selectedUnitId = $request->input('unit_id');
        $selectedEmployeeId = $request->input('employee_id');

        $units = Unit::orderBy('nama')->get();

        $employeeQuery = Employee::with(['unit'])
            ->where('status', true);

        if ($selectedUnitId) {
            $employeeQuery->where('unit_id', $selectedUnitId);
        }

        if ($selectedEmployeeId) {
            $employeeQuery->where('id', $selectedEmployeeId);
        }

        $allEmployees = Employee::where('status', true)->orderBy('nama')->get();
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

        $recapData = $employees->map(function ($emp) use ($attendances, $workingDays) {
            $empAttendances = $attendances->get($emp->id, collect());

            $hadir = $empAttendances->where('status', 'hadir')->count();
            $terlambat = $empAttendances->where('status', 'terlambat')->count();
            $izin = $empAttendances->where('status', 'izin')->count();
            $sakit = $empAttendances->where('status', 'sakit')->count();
            $cuti = $empAttendances->where('status', 'cuti')->count();
            $dinas = $empAttendances->filter(fn($a) => in_array($a->status, ['dinas', 'dinas_luar']))->count();

            $recordedDays = $hadir + $terlambat + $izin + $sakit + $cuti + $dinas;
            $alpha = max(0, $workingDays - $recordedDays);

            return (object) [
                'employee' => $emp,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'cuti' => $cuti,
                'dinas' => $dinas,
                'alpha' => $alpha,
                'total_tercatat' => $recordedDays,
            ];
        });

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('admin.recap.index', compact(
            'recapData',
            'units',
            'allEmployees',
            'selectedMonth',
            'selectedYear',
            'selectedUnitId',
            'selectedEmployeeId',
            'workingDays',
            'months'
        ));
    }
}
