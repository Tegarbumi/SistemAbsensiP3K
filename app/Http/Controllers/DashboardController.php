<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard sesuai role pengguna (PPPK atau Admin).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'pppk') {
            $employee = $user->employee;
            $today = Carbon::today();

            $todayAttendance = null;
            if ($employee) {
                $todayAttendance = Attendance::where('employee_id', $employee->id)
                    ->whereDate('tanggal', $today)
                    ->first();
            }

            // Ringkasan Bulan Berjalan dari Database
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $monthQuery = Attendance::where('employee_id', $employee?->id)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear);

            $stats = [
                'hadir' => (clone $monthQuery)->where('status', 'hadir')->count(),
                'terlambat' => (clone $monthQuery)->where('status', 'terlambat')->count(),
                'izin' => (clone $monthQuery)->where('status', 'izin')->count(),
                'sakit' => (clone $monthQuery)->where('status', 'sakit')->count(),
                'dinas' => (clone $monthQuery)->where('status', 'dinas')->count(),
                'cuti' => (clone $monthQuery)->where('status', 'cuti')->count(),
                'alpha' => (clone $monthQuery)->where('status', 'alpha')->count(),
            ];

            $namaBulan = Carbon::now()->locale('id')->isoFormat('MMMM Y');

            return view('dashboard', [
                'employee' => $employee,
                'todayAttendance' => $todayAttendance,
                'stats' => $stats,
                'namaBulan' => $namaBulan,
            ]);
        }

        // Statistik untuk Admin
        $today = Carbon::today();
        $adminStats = [
            'total_pppk' => Employee::count(),
            'pppk_aktif' => Employee::where('status', true)->count(),
            'total_unit' => Unit::count(),
            'hadir_hari_ini' => Attendance::whereDate('tanggal', $today)->where('status', 'hadir')->count(),
            'terlambat_hari_ini' => Attendance::whereDate('tanggal', $today)->where('status', 'terlambat')->count(),
        ];

        return view('dashboard', [
            'adminStats' => $adminStats,
        ]);
    }
}
