<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use App\Models\OfficeLocation;
use App\Models\Unit;
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

        // Ambil semua unit aktif untuk dropdown pemilihan
        $units = Unit::where('status', true)->orderBy('nama')->get();

        // Tentukan unit yang dipilih dan lokasi kantor berdasarkan pilihan
        $selectedUnit = null;
        $officeLocation = null;
        $isDinasLapangan = false;

        if ($attendance && $attendance->unit_id) {
            // Pegawai sudah check-in hari ini — gunakan unit yang dipilih
            $selectedUnit = Unit::find($attendance->unit_id);
        } elseif ($employee && $employee->unit_id) {
            // Default ke unit tempat pegawai terdaftar
            $selectedUnit = $units->firstWhere('id', $employee->unit_id);
        }

        if (!$selectedUnit) {
            $selectedUnit = $units->first();
        }

        if ($selectedUnit) {
            $isDinasLapangan = (bool) $selectedUnit->is_dinas_lapangan;
            $officeLocation = OfficeLocation::where('unit_id', $selectedUnit->id)
                ->where('status', true)
                ->first();
        }

        // Siapkan data units + office location untuk JavaScript
        $unitsWithOffice = $units->map(function ($unit) {
            $office = OfficeLocation::where('unit_id', $unit->id)
                ->where('status', true)
                ->first();
            return [
                'id' => $unit->id,
                'nama' => $unit->nama,
                'is_dinas_lapangan' => (bool) $unit->is_dinas_lapangan,
                'office_lat' => $office ? (float) $office->latitude : 0,
                'office_lng' => $office ? (float) $office->longitude : 0,
                'office_radius' => $office ? (int) $office->radius_meter : 0,
                'office_name' => $office ? $office->nama : '',
            ];
        });

        $now = Carbon::now();

        return view('employee.attendance.index', [
            'employee' => $employee,
            'attendance' => $attendance,
            'officeLocation' => $officeLocation,
            'isDinasLapangan' => $isDinasLapangan,
            'units' => $units,
            'unitsWithOffice' => $unitsWithOffice,
            'selectedUnitId' => $selectedUnit?->id,
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

        // Validasi input: toleransi akurasi GPS diperlonggar agar kompatibel dengan browser PC/WiFi & indoor
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|min:0|max:5000',
            'selfie' => 'required|image|max:2048',
            'unit_id' => 'nullable|exists:units,id',
        ], [
            'accuracy.max' => 'Akurasi GPS tidak valid atau sinyal lokasi terputus. Mohon pastikan GPS aktif.',
            'unit_id.exists' => 'Unit kerja tidak valid.',
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

        // Ambil unit yang dipilih pegawai (atau fallback ke unit asal pegawai)
        $chosenUnitId = $request->input('unit_id') ?: $employee->unit_id;
        $selectedUnit = $chosenUnitId ? Unit::find($chosenUnitId) : null;

        if (!$selectedUnit) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih unit kerja terlebih dahulu.',
            ], 422);
        }

        $isDinasLapangan = (bool) $selectedUnit->is_dinas_lapangan;

        // Ambil lokasi kantor berdasarkan unit yang dipilih
        $officeLocation = OfficeLocation::where('unit_id', $selectedUnit->id)
            ->where('status', true)
            ->first();

        if (!$officeLocation && !$isDinasLapangan) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi kantor belum diatur untuk unit ini. Hubungi admin.',
            ], 422);
        }

        // Hitung jarak ke kantor di SERVER (jika ada lokasi kantor)
        $distance = null;
        if ($officeLocation) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $officeLocation->latitude,
                $officeLocation->longitude
            );
        }

        // Simpan selfie melalui Laravel Storage
        $selfiePath = $request->file('selfie')->store('selfies', 'public');

        // Tentukan status kehadiran
        $now = Carbon::now();
        $keterangan = null;

        if ($isDinasLapangan) {
            // Unit Dinas Lapangan (Sistem Sharelok GPS)
            $status = 'dinas';
            $lokasiCustom = $request->input('lokasi_lapangan') ?: $request->input('keterangan');
            if ($lokasiCustom) {
                $keterangan = 'Dinas Lapangan (Sharelok): ' . trim($lokasiCustom);
            } else {
                $keterangan = 'Presensi Dinas Lapangan (Sistem Sharelok GPS)';
            }
        } else {
            $isOutsideRadius = $officeLocation && ($distance > $officeLocation->radius_meter);

            if ($isOutsideRadius) {
                // Jika di luar radius kantor, otomatis dikategorikan sebagai Dinas Luar / Lapangan
                $status = 'dinas';
                $keterangan = 'Absensi di luar radius kantor (Dinas Luar/Lapangan, Jarak: ' . round($distance) . 'm)';
            } else {
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
            }
        }

        // Buat record absensi
        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'unit_id' => $selectedUnit->id,
            'tanggal' => $today->toDateString(),
            'jam_masuk' => $now->format('H:i:s'),
            'latitude_masuk' => $request->latitude,
            'longitude_masuk' => $request->longitude,
            'accuracy_masuk' => $request->accuracy,
            'distance_masuk' => $distance !== null ? round($distance, 2) : null,
            'selfie_masuk' => $selfiePath,
            'status' => $status,
            'keterangan' => $keterangan,
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

        // Audit Trail log
        \App\Models\AuditLog::record(
            $user->id,
            'check_in',
            Attendance::class,
            $attendance->id,
            [
                'employee' => $employee->nama,
                'status' => $status,
                'distance' => round($distance, 2),
                'accuracy' => $request->accuracy,
            ]
        );

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

        // Validasi input: toleransi akurasi diperlonggar
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|min:0|max:5000',
            'selfie' => 'required|image|max:2048',
        ], [
            'accuracy.max' => 'Akurasi GPS tidak valid atau sinyal lokasi terputus. Mohon pastikan GPS aktif.',
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

        // Cek status unit dari absensi hari ini (atau fallback ke unit pegawai)
        $selectedUnitId = $attendance->unit_id ?: $employee->unit_id;
        $selectedUnit = $selectedUnitId ? Unit::find($selectedUnitId) : null;
        $isDinasLapangan = (bool) ($selectedUnit?->is_dinas_lapangan);

        // Ambil lokasi kantor berdasarkan unit absensi
        $officeLocation = null;
        if ($selectedUnit) {
            $officeLocation = OfficeLocation::where('unit_id', $selectedUnit->id)
                ->where('status', true)
                ->first();
        }

        if (!$officeLocation) {
            $officeLocation = OfficeLocation::where('status', true)->first();
        }

        if (!$officeLocation && !$isDinasLapangan) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi kantor belum diatur. Hubungi admin.',
            ], 422);
        }

        // Hitung jarak ke kantor di SERVER (jika ada lokasi kantor)
        $distance = null;
        if ($officeLocation) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $officeLocation->latitude,
                $officeLocation->longitude
            );
        }

        // Simpan selfie
        $selfiePath = $request->file('selfie')->store('selfies', 'public');

        $now = Carbon::now();

        // Update record absensi
        $updateData = [
            'jam_pulang' => $now->format('H:i:s'),
            'latitude_pulang' => $request->latitude,
            'longitude_pulang' => $request->longitude,
            'accuracy_pulang' => $request->accuracy,
            'distance_pulang' => $distance !== null ? round($distance, 2) : null,
            'selfie_pulang' => $selfiePath,
        ];

        // Jika bukan dinas lapangan dan pulang di luar radius kantor, tambahkan keterangan
        if (!$isDinasLapangan && $officeLocation && ($distance > $officeLocation->radius_meter) && empty($attendance->keterangan)) {
            $updateData['keterangan'] = 'Check-out di luar radius kantor (' . round($distance) . 'm)';
        }

        $attendance->update($updateData);

        // Simpan ke attendance_locations
        AttendanceLocation::create([
            'attendance_id' => $attendance->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'recorded_at' => $now,
        ]);

        // Audit Trail log
        \App\Models\AuditLog::record(
            $user->id,
            'check_out',
            Attendance::class,
            $attendance->id,
            [
                'employee' => $employee->nama,
                'status' => $attendance->status,
                'distance' => round($distance, 2),
                'accuracy' => $request->accuracy,
            ]
        );

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
     * Reset / Batalkan absensi hari ini agar pegawai dapat melakukan absensi ulang (koreksi).
     */
    public function resetAttendance(Request $request): JsonResponse
    {
        $user = $request->user();
        $employee = $user->employee;

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan. Hubungi admin.',
            ], 422);
        }

        $today = Carbon::today();

        // Cari data absensi hari ini
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data absensi hari ini yang dapat di-reset.',
            ], 422);
        }

        $request->validate([
            'alasan' => 'nullable|string|max:255',
        ]);

        $alasan = $request->input('alasan') ?: 'Koreksi kesalahan input absensi oleh pegawai';

        // Catat audit log forensik lengkap sebelum dihapus
        \App\Models\AuditLog::record(
            $user->id,
            'attendance_reset',
            Attendance::class,
            $attendance->id,
            [
                'employee' => $employee->nama,
                'nip' => $employee->nip,
                'alasan' => $alasan,
                'previous_attendance' => [
                    'unit_id' => $attendance->unit_id,
                    'unit_nama' => $attendance->unit?->nama,
                    'status' => $attendance->status,
                    'jam_masuk' => $attendance->jam_masuk,
                    'jam_pulang' => $attendance->jam_pulang,
                    'latitude_masuk' => $attendance->latitude_masuk,
                    'longitude_masuk' => $attendance->longitude_masuk,
                    'keterangan' => $attendance->keterangan,
                ],
            ]
        );

        // Hapus file selfie dari storage jika ada
        if ($attendance->selfie_masuk && Storage::disk('public')->exists($attendance->selfie_masuk)) {
            Storage::disk('public')->delete($attendance->selfie_masuk);
        }
        if ($attendance->selfie_pulang && Storage::disk('public')->exists($attendance->selfie_pulang)) {
            Storage::disk('public')->delete($attendance->selfie_pulang);
        }

        // Hapus riwayat lokasi gps terkait
        $attendance->locations()->delete();

        // Hapus record absensi
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil di-reset. Silakan lakukan absensi ulang dengan memilih unit kerja yang sesuai.',
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
