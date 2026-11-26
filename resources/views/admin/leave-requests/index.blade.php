<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight">
                    {{ __('Persetujuan Izin / Sakit / Cuti') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dinas Kearsipan dan Perpustakaan Kabupaten Subang</p>
            </div>
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

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white dark:bg-slate-800 p-3.5 sm:p-4 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide block">Total</span>
                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums">{{ $summary['total'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Pengajuan</span>
                </div>
                <div class="bg-amber-50/60 dark:bg-amber-950/20 p-3.5 sm:p-4 rounded-2xl border border-amber-200/60 dark:border-amber-800/50 shadow-2xs text-center">
                    <span class="text-[11px] font-semibold text-amber-700 dark:text-amber-400 uppercase tracking-wide block">Menunggu</span>
                    <span class="text-2xl font-black text-amber-700 dark:text-amber-400 tabular-nums">{{ $summary['menunggu'] }}</span>
                    <span class="text-[10px] text-amber-500/70 block mt-0.5">Persetujuan</span>
                </div>
                <div class="bg-emerald-50/60 dark:bg-emerald-950/20 p-3.5 sm:p-4 rounded-2xl border border-emerald-200/60 dark:border-emerald-800/50 shadow-2xs text-center">
                    <span class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide block">Disetujui</span>
                    <span class="text-2xl font-black text-emerald-700 dark:text-emerald-400 tabular-nums">{{ $summary['disetujui'] }}</span>
                    <span class="text-[10px] text-emerald-500/70 block mt-0.5">Permohonan</span>
                </div>
                <div class="bg-rose-50/60 dark:bg-rose-950/20 p-3.5 sm:p-4 rounded-2xl border border-rose-200/60 dark:border-rose-800/50 shadow-2xs text-center">
                    <span class="text-[11px] font-semibold text-rose-700 dark:text-rose-400 uppercase tracking-wide block">Ditolak</span>
                    <span class="text-2xl font-black text-rose-700 dark:text-rose-400 tabular-nums">{{ $summary['ditolak'] }}</span>
                    <span class="text-[10px] text-rose-500/70 block mt-0.5">Permohonan</span>
                </div>
            </div>

            {{-- Filter Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-5 shadow-xs transition-colors duration-200">
                <form method="GET" action="{{ route('admin.leave-requests.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="search" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Cari PPPK</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIP..."
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" id="status"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            <option value="all">Semua Status</option>
                            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label for="jenis" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Jenis</label>
                        <select name="jenis" id="jenis"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            <option value="all">Semua Jenis</option>
                            <option value="izin" {{ request('jenis') === 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('jenis') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="cuti" {{ request('jenis') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="dinas" {{ request('jenis') === 'dinas' ? 'selected' : '' }}>Dinas</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.leave-requests.index') }}"
                           class="px-3.5 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl transition text-center border border-slate-200 dark:border-slate-600">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors duration-200">
                <div class="overflow-x-auto -webkit-overflow-scrolling-touch">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-800/90 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 font-bold">Pegawai PPPK</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 font-bold">Jenis</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 font-bold">Tanggal</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 font-bold">Alasan</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 text-center font-bold">Dok.</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 text-center font-bold">Status</th>
                                <th scope="col" class="px-4 sm:px-5 py-3.5 text-right font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                            @forelse($leaveRequests as $req)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition">
                                    {{-- Pegawai --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm">{{ $req->employee?->nama ?: '-' }}</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">NIP: {{ $req->employee?->nip ?: '-' }}</div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500">{{ $req->employee?->unit?->nama ?: '-' }}</div>
                                    </td>

                                    {{-- Jenis --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                        @php
                                            $badgeClasses = match($req->jenis) {
                                                'izin'  => 'bg-blue-50 text-blue-700 border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/60',
                                                'sakit' => 'bg-purple-50 text-purple-700 border-purple-200/60 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/60',
                                                'cuti'  => 'bg-cyan-50 text-cyan-700 border-cyan-200/60 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-800/60',
                                                'dinas' => 'bg-indigo-50 text-indigo-700 border-indigo-200/60 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/60',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
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
                                    <td class="px-4 sm:px-5 py-3.5 max-w-[140px] sm:max-w-xs">
                                        <div class="text-xs text-slate-700 dark:text-slate-300 truncate">{{ $req->alasan }}</div>
                                    </td>

                                    {{-- Dokumen --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-center">
                                        @if($req->dokumen)
                                            <a href="{{ asset('storage/' . $req->dokumen) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-xs">
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
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>Disetujui
                                            </span>
                                        @elseif($req->status === 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0 animate-pulse"></span>Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($req->status === 'menunggu')
                                                {{-- Setujui --}}
                                                <form action="{{ route('admin.leave-requests.approve', $req) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Setujui pengajuan {{ $req->jenis }} ini? Data absensi akan otomatis dicatat.');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-lg transition font-semibold text-xs shadow-2xs">
                                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        <span class="hidden xs:inline">Setujui</span>
                                                    </button>
                                                </form>

                                                {{-- Tolak --}}
                                                <form action="{{ route('admin.leave-requests.reject', $req) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('Tolak pengajuan {{ $req->jenis }} ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-lg transition font-semibold text-xs shadow-2xs">
                                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        <span class="hidden xs:inline">Tolak</span>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Detail --}}
                                            <a href="{{ route('admin.leave-requests.show', $req) }}"
                                               class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/50 transition shadow-2xs"
                                               title="Lihat Rincian">
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tidak ada pengajuan ditemukan</p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500">Coba ubah filter pencarian di atas.</p>
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
