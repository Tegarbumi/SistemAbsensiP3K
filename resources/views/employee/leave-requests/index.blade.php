<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col xs:flex-row justify-between items-start xs:items-center gap-3">
            <div>
                <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight">
                    {{ __('Pengajuan Izin / Sakit / Cuti') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola permohonan izin dan pantau status persetujuan.</p>
            </div>
            <a href="{{ route('leave-requests.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition shrink-0">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Ajukan Permohonan</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-5">

            {{-- Flash Alert --}}
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-2xs">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Tabel Riwayat Pengajuan --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors duration-200">

                {{-- Card Header --}}
                <div class="px-4 sm:px-6 py-4 border-b border-slate-100 dark:border-slate-700/80 flex flex-col xs:flex-row xs:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Riwayat Pengajuan</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Semua permohonan izin, sakit, dan cuti Anda</p>
                    </div>
                    @if($leaveRequests->count() > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 shrink-0">
                            {{ $leaveRequests->total() }} permohonan
                        </span>
                    @endif
                </div>

                {{-- Scrollable Table --}}
                <div class="overflow-x-auto -webkit-overflow-scrolling-touch">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-800/90 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 font-bold">Jenis</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 font-bold">Tanggal</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 font-bold">Alasan</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 text-center font-bold">Dokumen</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 text-center font-bold">Status</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 text-right font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                            @forelse($leaveRequests as $req)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition">
                                    {{-- Jenis --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                        @php
                                            $jenisBadge = match($req->jenis) {
                                                'izin'  => 'bg-blue-50 text-blue-700 border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/60',
                                                'sakit' => 'bg-purple-50 text-purple-700 border-purple-200/60 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/60',
                                                'cuti'  => 'bg-cyan-50 text-cyan-700 border-cyan-200/60 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-800/60',
                                                'dinas' => 'bg-indigo-50 text-indigo-700 border-indigo-200/60 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/60',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $jenisBadge }}">
                                            {{ ucfirst($req->jenis) }}
                                        </span>
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-semibold text-slate-900 dark:text-white text-xs">
                                            {{ \Carbon\Carbon::parse($req->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }}
                                        </div>
                                        @if($req->tanggal_mulai != $req->tanggal_selesai)
                                            <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                                s/d {{ \Carbon\Carbon::parse($req->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Alasan --}}
                                    <td class="px-4 sm:px-5 py-3.5 max-w-[160px] sm:max-w-xs">
                                        <div class="text-xs text-slate-700 dark:text-slate-300 truncate">{{ $req->alasan }}</div>
                                    </td>

                                    {{-- Dokumen --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-center">
                                        @if($req->dokumen)
                                            <a href="{{ asset('storage/' . $req->dokumen) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-xs transition">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-slate-300 dark:text-slate-600">—</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-center">
                                        @if($req->status === 'disetujui')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                                Disetujui
                                            </span>
                                        @elseif($req->status === 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0 animate-pulse"></span>
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-right">
                                        <a href="{{ route('leave-requests.show', $req) }}"
                                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/50 rounded-lg transition font-semibold shadow-2xs text-xs">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Detail</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada permohonan</p>
                                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Klik tombol "Ajukan Permohonan" untuk membuat pengajuan baru.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leaveRequests->hasPages())
                    <div class="px-4 sm:px-6 py-4 border-t border-slate-100 dark:border-slate-700/60">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
