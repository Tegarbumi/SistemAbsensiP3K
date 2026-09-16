<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Permohonan Izin / Sakit') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">{{ $leaveRequest->employee?->nama }} &bull; Diajukan {{ $leaveRequest->created_at->locale('id')->isoFormat('D MMMM Y HH:mm') }} WIB</p>
            </div>
            <a href="{{ route('admin.leave-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Card Pegawai -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xl">
                        {{ substr($leaveRequest->employee?->nama ?: 'P', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $leaveRequest->employee?->nama }}</h3>
                        <div class="text-xs text-gray-500 space-x-2">
                            <span>NIP: {{ $leaveRequest->employee?->nip ?: '-' }}</span> &bull;
                            <span>Jabatan: {{ $leaveRequest->employee?->jabatan ?: '-' }}</span> &bull;
                            <span>Unit: {{ $leaveRequest->employee?->unit?->nama ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Rincian Permohonan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wider block font-semibold">Tipe Pengajuan</span>
                        <h4 class="text-xl font-bold text-gray-900 capitalize">{{ $leaveRequest->jenis }}</h4>
                    </div>
                    <div>
                        @if($leaveRequest->status === 'disetujui')
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Disetujui
                            </span>
                        @elseif($leaveRequest->status === 'ditolak')
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-red-100 text-red-800 border border-red-300">
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Persetujuan
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs text-gray-400 block mb-1">Tanggal Mulai:</span>
                        <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($leaveRequest->tanggal_mulai)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs text-gray-400 block mb-1">Tanggal Selesai:</span>
                        <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($leaveRequest->tanggal_selesai)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-semibold text-gray-700 mb-1">Alasan Pengajuan:</h5>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 text-sm text-gray-800 leading-relaxed">
                        {{ $leaveRequest->alasan }}
                    </div>
                </div>

                <!-- Dokumen Pendukung -->
                <div>
                    <h5 class="text-sm font-semibold text-gray-700 mb-1">Berkas Dokumen Pendukung:</h5>
                    @if($leaveRequest->dokumen)
                        <div class="flex items-center justify-between p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
                            <div class="flex items-center space-x-2 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Berkas Dokumen Lampiran</span>
                            </div>
                            <a href="{{ asset('storage/' . $leaveRequest->dokumen) }}" target="_blank" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow transition">
                                Unduh / Buka Dokumen
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada dokumen pendukung yang dilampirkan.</p>
                    @endif
                </div>

                @if($leaveRequest->status !== 'menunggu')
                    <div class="pt-4 border-t border-gray-200 text-xs text-gray-500">
                        <span>Diproses oleh: <strong>{{ $leaveRequest->approver?->name ?: 'Administrator' }}</strong></span> &bull;
                        <span>Pada: <strong>{{ $leaveRequest->approved_at ? $leaveRequest->approved_at->locale('id')->isoFormat('D MMMM Y HH:mm') : '-' }} WIB</strong></span>
                    </div>
                @else
                    <!-- Tombol Aksi Persetujuan -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <form action="{{ route('admin.leave-requests.reject', $leaveRequest) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK permohonan ini?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 border border-red-300 text-red-700 hover:bg-red-50 rounded-lg text-sm font-semibold transition">
                                Tolak Permohonan
                            </button>
                        </form>

                        <form action="{{ route('admin.leave-requests.approve', $leaveRequest) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI permohonan ini? Data kehadiran akan otomatis dibuat/diperbarui.');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow transition">
                                Setujui Permohonan
                            </button>
                        </form>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
