<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    /**
     * Menampilkan daftar pengajuan izin / sakit milik PPPK yang login.
     */
    public function index(Request $request): View
    {
        $employee = $request->user()->employee;

        $leaveRequests = LeaveRequest::where('employee_id', $employee?->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employee.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'employee' => $employee,
        ]);
    }

    /**
     * Menampilkan formulir permohonan izin / sakit / cuti / dinas.
     */
    public function create(Request $request): View
    {
        $employee = $request->user()->employee;

        return view('employee.leave-requests.create', [
            'employee' => $employee,
        ]);
    }

    /**
     * Menyimpan permohonan izin baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $employee = $request->user()->employee;
        if (!$employee) {
            abort(403, 'Data pegawai tidak ditemukan.');
        }

        $validated = $request->validate([
            'jenis' => ['required', 'in:izin,sakit,cuti,dinas'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan' => ['required', 'string'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ], [
            'jenis.required' => 'Jenis pengajuan wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'alasan.required' => 'Alasan pengajuan wajib diisi.',
            'dokumen.mimes' => 'Dokumen harus berupa file PDF, JPG, JPEG, atau PNG.',
            'dokumen.max' => 'Ukuran berkas dokumen maksimal 2MB.',
        ]);

        $dokumenPath = null;
        if ($request->hasFile('dokumen')) {
            $dokumenPath = $request->file('dokumen')->store('leave-documents', 'public');
        }

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'jenis' => $validated['jenis'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'alasan' => trim($validated['alasan']),
            'dokumen' => $dokumenPath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Permohonan ' . ucfirst($validated['jenis']) . ' berhasil diajukan dan sedang menunggu persetujuan admin.');
    }

    /**
     * Menampilkan detail pengajuan izin (hanya milik pegawai yang bersangkutan).
     */
    public function show(Request $request, LeaveRequest $leaveRequest): View
    {
        $employee = $request->user()->employee;

        if (!$employee || $leaveRequest->employee_id !== $employee->id) {
            abort(403, 'Anda tidak memiliki akses ke data pengajuan ini.');
        }

        $leaveRequest->load('approver');

        return view('employee.leave-requests.show', [
            'leaveRequest' => $leaveRequest,
            'employee' => $employee,
        ]);
    }
}
