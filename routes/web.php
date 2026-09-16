<?php

use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::patch('/employees/{employee}/toggle-status', [
            \App\Http\Controllers\Admin\EmployeeController::class,
            'toggleStatus'
        ])->name('admin.employees.toggle-status');

        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class)
            ->names('admin.employees');

        Route::patch('/units/{unit}/toggle-status', [
            \App\Http\Controllers\Admin\UnitController::class,
            'toggleStatus'
        ])->name('admin.units.toggle-status');

        Route::resource('units', \App\Http\Controllers\Admin\UnitController::class)
            ->names('admin.units');

        Route::patch('/lokasi-kantor/{lokasiKantor}/toggle-status', [
            \App\Http\Controllers\Admin\OfficeLocationController::class,
            'toggleStatus'
        ])->name('admin.office-locations.toggle-status');

        Route::resource('lokasi-kantor', \App\Http\Controllers\Admin\OfficeLocationController::class)
            ->parameters(['lokasi-kantor' => 'lokasiKantor'])
            ->names('admin.office-locations');

        Route::patch('/jadwal-kerja/{jadwalKerja}/toggle-status', [
            \App\Http\Controllers\Admin\WorkScheduleController::class,
            'toggleStatus'
        ])->name('admin.work-schedules.toggle-status');

        Route::resource('jadwal-kerja', \App\Http\Controllers\Admin\WorkScheduleController::class)
            ->parameters(['jadwal-kerja' => 'jadwalKerja'])
            ->names('admin.work-schedules');

        Route::get('/absensi', [
            \App\Http\Controllers\Admin\AttendanceMonitoringController::class,
            'index'
        ])->name('admin.attendance-monitoring.index');

        Route::get('/absensi/{attendance}', [
            \App\Http\Controllers\Admin\AttendanceMonitoringController::class,
            'show'
        ])->name('admin.attendance-monitoring.show');

        Route::get('/monitoring-gps', [
            \App\Http\Controllers\Admin\GpsMonitoringController::class,
            'index'
        ])->name('admin.gps-monitoring.index');

        Route::patch('/pengajuan-izin/{leaveRequest}/approve', [
            \App\Http\Controllers\Admin\LeaveRequestController::class,
            'approve'
        ])->name('admin.leave-requests.approve');

        Route::patch('/pengajuan-izin/{leaveRequest}/reject', [
            \App\Http\Controllers\Admin\LeaveRequestController::class,
            'reject'
        ])->name('admin.leave-requests.reject');

        Route::get('/rekap', [
            \App\Http\Controllers\Admin\RecapController::class,
            'index'
        ])->name('admin.recap.index');

        Route::get('/laporan', [
            \App\Http\Controllers\Admin\ReportController::class,
            'index'
        ])->name('admin.reports.index');

        Route::get('/laporan/export-pdf', [
            \App\Http\Controllers\Admin\ReportController::class,
            'exportPdf'
        ])->name('admin.reports.export-pdf');

        Route::get('/laporan/export-excel', [
            \App\Http\Controllers\Admin\ReportController::class,
            'exportExcel'
        ])->name('admin.reports.export-excel');

        Route::get('/pengajuan-izin', [
            \App\Http\Controllers\Admin\LeaveRequestController::class,
            'index'
        ])->name('admin.leave-requests.index');

        Route::get('/pengajuan-izin/{leaveRequest}', [
            \App\Http\Controllers\Admin\LeaveRequestController::class,
            'show'
        ])->name('admin.leave-requests.show');

    });

Route::middleware(['auth', 'role:pppk'])
    ->group(function () {

        Route::get('/absensi', [
            AttendanceController::class,
            'index'
        ])->name('attendance.index');

        Route::get('/absensi/status', [
            AttendanceController::class,
            'status'
        ])->name('attendance.status');

        Route::post('/absensi/check-in', [
            AttendanceController::class,
            'checkIn'
        ])->name('attendance.checkin');

        Route::post('/absensi/check-out', [
            AttendanceController::class,
            'checkOut'
        ])->name('attendance.checkout');

        Route::get('/riwayat-absensi', [
            \App\Http\Controllers\Employee\AttendanceHistoryController::class,
            'index'
        ])->name('attendance.history.index');

        Route::get('/riwayat-absensi/{attendance}', [
            \App\Http\Controllers\Employee\AttendanceHistoryController::class,
            'show'
        ])->name('attendance.history.show');

        Route::get('/pengajuan-izin', [
            \App\Http\Controllers\Employee\LeaveRequestController::class,
            'index'
        ])->name('leave-requests.index');

        Route::get('/pengajuan-izin/create', [
            \App\Http\Controllers\Employee\LeaveRequestController::class,
            'create'
        ])->name('leave-requests.create');

        Route::post('/pengajuan-izin', [
            \App\Http\Controllers\Employee\LeaveRequestController::class,
            'store'
        ])->name('leave-requests.store');

        Route::get('/pengajuan-izin/{leaveRequest}', [
            \App\Http\Controllers\Employee\LeaveRequestController::class,
            'show'
        ])->name('leave-requests.show');

    });

require __DIR__.'/auth.php';
