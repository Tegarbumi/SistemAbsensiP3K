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

    });

require __DIR__.'/auth.php';
