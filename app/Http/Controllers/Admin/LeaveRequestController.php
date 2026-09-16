<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan izin / sakit PPPK.
     */
    public function index(Request $request): View
    {
        $query = LeaveRequest::with(['employee.unit', 'approver']);

        // Filter status (menunggu / disetujui / ditolak)
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Filter jenis (izin / sakit / cuti / dinas)
        if ($jenis = $request->input('jenis')) {
            if ($jenis !== 'all') {
                $query->where('jenis', $jenis);
            }
        }

        // Filter pencarian nama pegawai / NIP
        if ($search = $request->input('search')) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $summary = [
            'total' => LeaveRequest::count(),
            'menunggu' => LeaveRequest::where('status', 'menunggu')->count(),
            'disetujui' => LeaveRequest::where('status', 'disetujui')->count(),
            'ditolak' => LeaveRequest::where('status', 'ditolak')->count(),
        ];

        $leaveRequests = $query->orderByRaw("CASE WHEN status = 'menunggu' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'summary' => $summary,
        ]);
    }

    /**
     * Menampilkan detail permohonan izin / sakit.
     */
    public function show(LeaveRequest $leaveRequest): View
    {
        $leaveRequest->load(['employee.unit', 'approver']);

        return view('admin.leave-requests.show', [
            'leaveRequest' => $leaveRequest,
        ]);
    }

    /**
     * Menyetujui pengajuan izin dan membuat / memperbarui record kehadiran di tabel attendances.
     */
    public function approve(LeaveRequest $leaveRequest): RedirectResponse
    {
        DB::transaction(function () use ($leaveRequest) {
            $leaveRequest->update([
                'status' => 'disetujui',
                'approved_by' => auth()->id(),
                'approved_at' => Carbon::now(),
            ]);

            // Sinkronkan ke tabel attendances untuk setiap tanggal dalam rentang
            $period = CarbonPeriod::create($leaveRequest->tanggal_mulai, $leaveRequest->tanggal_selesai);

            foreach ($period as $date) {
                $tanggalStr = $date->toDateString();

                $attendance = Attendance::where('employee_id', $leaveRequest->employee_id)
                    ->whereDate('tanggal', $tanggalStr)
                    ->first();

                $keteranganText = ucfirst($leaveRequest->jenis) . ' (' . $leaveRequest->alasan . ')';

                if ($attendance) {
                    $attendance->update([
                        'status' => $leaveRequest->jenis,
                        'keterangan' => $keteranganText,
                    ]);
                } else {
                    Attendance::create([
                        'employee_id' => $leaveRequest->employee_id,
                        'tanggal' => $tanggalStr,
                        'status' => $leaveRequest->jenis,
                        'keterangan' => $keteranganText,
                    ]);
                }
            }

            // Audit Trail log
            \App\Models\AuditLog::record(
                auth()->id(),
                'approve_leave',
                LeaveRequest::class,
                $leaveRequest->id,
                [
                    'employee_id' => $leaveRequest->employee_id,
                    'jenis' => $leaveRequest->jenis,
                    'periode' => $leaveRequest->tanggal_mulai->format('Y-m-d') . ' s/d ' . $leaveRequest->tanggal_selesai->format('Y-m-d'),
                ]
            );
        });

        return redirect()->back()
            ->with('success', 'Permohonan ' . ucfirst($leaveRequest->jenis) . ' telah disetujui dan dicatat pada rekap absensi.');
    }

    /**
     * Menolak permohonan izin.
     */
    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        // Audit Trail log
        \App\Models\AuditLog::record(
            auth()->id(),
            'reject_leave',
            LeaveRequest::class,
            $leaveRequest->id,
            [
                'employee_id' => $leaveRequest->employee_id,
                'jenis' => $leaveRequest->jenis,
            ]
        );

        return redirect()->back()
            ->with('success', 'Permohonan ' . ucfirst($leaveRequest->jenis) . ' telah ditolak.');
    }
}
