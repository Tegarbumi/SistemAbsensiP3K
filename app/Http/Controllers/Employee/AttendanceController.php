<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\OfficeLocation;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman absensi.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $employee = $user->employee;

        $today = Carbon::today();

        // Ambil absensi hari ini jika ada
        $attendance = null;
        if ($employee) {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('tanggal', $today)
                ->first();
        }

        // Ambil lokasi kantor aktif berdasarkan unit pegawai
        $officeLocation = null;
        if ($employee && $employee->unit_id) {
            $officeLocation = OfficeLocation::where('unit_id', $employee->unit_id)
                ->where('status', true)
                ->first();
        }

        // Jika tidak ada lokasi unit-specific, ambil lokasi aktif manapun
        if (!$officeLocation) {
            $officeLocation = OfficeLocation::where('status', true)->first();
        }

        $now = Carbon::now();

        return view('employee.attendance.index', [
            'employee' => $employee,
            'attendance' => $attendance,
            'officeLocation' => $officeLocation,
            'serverTime' => $now->format('H:i:s'),
            'serverDate' => $today->toDateString(),
            'serverDateFormatted' => $this->formatTanggalIndonesia($today),
        ]);
    }

    /**
     * Endpoint JSON untuk AJAX polling status dan jam server.
     */
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();
        $employee = $user->employee;

        $today = Carbon::today();
        $attendance = null;
        $hasCheckedIn = false;
        $hasCheckedOut = false;

        if ($employee) {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('tanggal', $today)
                ->first();

            if ($attendance) {
                $hasCheckedIn = !is_null($attendance->jam_masuk);
                $hasCheckedOut = !is_null($attendance->jam_pulang);
            }
        }

        return response()->json([
            'server_time' => Carbon::now()->format('H:i:s'),
            'has_checked_in' => $hasCheckedIn,
            'has_checked_out' => $hasCheckedOut,
            'attendance' => $attendance ? [
                'jam_masuk' => $attendance->jam_masuk,
                'jam_pulang' => $attendance->jam_pulang,
                'status' => $attendance->status,
            ] : null,
        ]);
    }

    /**
     * Proses check-in absensi.
     */
    public function checkIn(Request $request): JsonResponse
    {
        $user = $request->user();
        $employee = $user->employee;

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan. Hubungi admin.',
            ], 422);
        }

        // Validasi input
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|min:0',
            'selfie' => 'required|image|max:2048',
        ]);

        $today = Carbon::today();

        // Cek apakah sudah check-in hari ini
        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini.',
            ], 422);
        }

        // Ambil lokasi kantor
        $officeLocation = OfficeLocation::where('unit_id', $employee->unit_id)
            ->where('status', true)
            ->first();

        if (!$officeLocation) {
            $officeLocation = OfficeLocation::where('status', true)->first();
        }

        if (!$officeLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi kantor belum diatur. Hubungi admin.',
            ], 422);
        }

        // Validasi radius di SERVER
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $officeLocation->latitude,
            $officeLocation->longitude
        );

        if ($distance > $officeLocation->radius_meter) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius lokasi absensi.',
                'distance' => round($distance),
                'radius' => $officeLocation->radius_meter,
            ], 422);
        }

        // Simpan selfie melalui Laravel Storage
        $selfiePath = $request->file('selfie')->store('selfies', 'public');

        // Tentukan status hadir/terlambat
        $now = Carbon::now();
        $status = 'hadir';

        $dayNames = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            0 => 'minggu',
        ];
        $dayColumn = $dayNames[$now->dayOfWeek] ?? 'senin';

        $workSchedule = WorkSchedule::where('status', true)
            ->where($dayColumn, true)
            ->first();

        if (!$workSchedule) {
            $workSchedule = WorkSchedule::where('status', true)->first() ?: WorkSchedule::first();
        }

        if ($workSchedule) {
            $jamMasuk = Carbon::parse($workSchedule->jam_masuk);
            $batasTerlambat = $jamMasuk->copy()->addMinutes($workSchedule->toleransi_terlambat);

            if ($now->format('H:i:s') > $batasTerlambat->format('H:i:s')) {
                $status = 'terlambat';
            }
        }

        // Buat record absensi
        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'tanggal' => $today->toDateString(),
            'jam_masuk' => $now->format('H:i:s'),
            'latitude_masuk' => $request->latitude,
            'longitude_masuk' => $request->longitude,
            'accuracy_masuk' => $request->accuracy,
            'distance_masuk' => round($distance, 2),
            'selfie_masuk' => $selfiePath,
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Simpan ke attendance_locations
        AttendanceLocation::create([
            'attendance_id' => $attendance->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'recorded_at' => $now,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil pada pukul ' . $now->format('H:i:s') . '.',
            'attendance' => [
                'jam_masuk' => $attendance->jam_masuk,
                'status' => $attendance->status,
            ],
        ]);
    }

    /**
     * Proses check-out absensi.
     */
    public function checkOut(Request $request): JsonResponse
    {
        $user = $request->user();
        $employee = $user->employee;

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan. Hubungi admin.',
            ], 422);
        }

        // Validasi input
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|min:0',
            'selfie' => 'required|image|max:2048',
        ]);

        $today = Carbon::today();

        // Cek absensi hari ini
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan absensi masuk.',
            ], 422);
        }

        if (!is_null($attendance->jam_pulang)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi pulang hari ini.',
            ], 422);
        }

        // Ambil lokasi kantor
        $officeLocation = OfficeLocation::where('unit_id', $employee->unit_id)
            ->where('status', true)
            ->first();

        if (!$officeLocation) {
            $officeLocation = OfficeLocation::where('status', true)->first();
        }

        if (!$officeLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi kantor belum diatur. Hubungi admin.',
            ], 422);
        }

        // Validasi radius di SERVER
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $officeLocation->latitude,
            $officeLocation->longitude
        );

        if ($distance > $officeLocation->radius_meter) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius lokasi absensi.',
                'distance' => round($distance),
                'radius' => $officeLocation->radius_meter,
            ], 422);
        }

        // Simpan selfie
        $selfiePath = $request->file('selfie')->store('selfies', 'public');

        $now = Carbon::now();

        // Update record absensi
        $attendance->update([
            'jam_pulang' => $now->format('H:i:s'),
            'latitude_pulang' => $request->latitude,
            'longitude_pulang' => $request->longitude,
            'accuracy_pulang' => $request->accuracy,
            'distance_pulang' => round($distance, 2),
            'selfie_pulang' => $selfiePath,
        ]);

        // Simpan ke attendance_locations
        AttendanceLocation::create([
            'attendance_id' => $attendance->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'recorded_at' => $now,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil pada pukul ' . $now->format('H:i:s') . '.',
            'attendance' => [
                'jam_masuk' => $attendance->jam_masuk,
                'jam_pulang' => $attendance->jam_pulang,
                'status' => $attendance->status,
            ],
        ]);
    }

    /**
     * Menghitung jarak antara dua titik koordinat menggunakan formula Haversine.
     *
     * @param float $lat1 Latitude titik 1
     * @param float $lon1 Longitude titik 1
     * @param float $lat2 Latitude titik 2
     * @param float $lon2 Longitude titik 2
     * @return float Jarak dalam meter
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Format tanggal ke format Indonesia (contoh: Senin, 15 September 2026).
     */
    private function formatTanggalIndonesia(Carbon $date): string
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

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $namaHari = $hari[$date->format('l')] ?? $date->format('l');
        $namaBulan = $bulan[(int) $date->format('n')] ?? $date->format('F');

        return $namaHari . ', ' . $date->format('d') . ' ' . $namaBulan . ' ' . $date->format('Y');
    }
}
