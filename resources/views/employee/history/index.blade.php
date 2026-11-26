<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <span>{{ __('Riwayat Presensi Pegawai') }}</span>
            </h2>
            <a href="{{ route('attendance.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Buka Presensi Hari Ini</span>
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            <!-- Filter Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200">
                <form method="GET" action="{{ route('attendance.history.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 items-end">
                    <div>
                        <label for="bulan" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bulan</label>
                        <select name="bulan" id="bulan" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 shadow-2xs text-xs sm:text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all" {{ $selectedMonth === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @foreach($daftarBulan as $num => $nama)
                                <option value="{{ $num }}" {{ (string)$selectedMonth === (string)$num ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tahun" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Tahun</label>
                        <select name="tahun" id="tahun" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 shadow-2xs text-xs sm:text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @foreach($daftarTahun as $thn)
                                <option value="{{ $thn }}" {{ (string)$selectedYear === (string)$thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status Kehadiran</label>
                        <select name="status" id="status" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 shadow-2xs text-xs sm:text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="hadir" {{ $selectedStatus === 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ $selectedStatus === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="izin" {{ $selectedStatus === 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ $selectedStatus === 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="dinas" {{ $selectedStatus === 'dinas' ? 'selected' : '' }}>Dinas</option>
                            <option value="cuti" {{ $selectedStatus === 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="alpha" {{ $selectedStatus === 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition cursor-pointer">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('attendance.history.index') }}" class="px-3.5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl transition text-center border border-slate-200 dark:border-slate-600">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Visualisasi Kalender Ringkas (Jika Bulan dan Tahun dipilih) -->
            @if(!empty($calendarDays))
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 pb-3 mb-4 border-b border-slate-100 dark:border-slate-700/80">
                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            Kalender Kehadiran: {{ $daftarBulan[(int)$selectedMonth] ?? '' }} {{ $selectedYear }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[11px] text-slate-500 dark:text-slate-400">
                            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-1 shrink-0"></span> Hadir</span>
                            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-amber-500 mr-1 shrink-0"></span> Terlambat</span>
                            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-blue-500 mr-1 shrink-0"></span> Izin/Cuti</span>
                            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600 mr-1 shrink-0"></span> Kosong</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 gap-1 sm:gap-2 text-center">
                        @foreach($calendarDays as $dayItem)
                            @php
                                $statusColor = 'bg-slate-50 dark:bg-slate-900/60 border-slate-200/70 dark:border-slate-700/60 text-slate-600 dark:text-slate-400';
                                if ($dayItem['attendance']) {
                                    $st = $dayItem['attendance']->status;
                                    if ($st === 'hadir') {
                                        $statusColor = 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 font-bold';
                                    } elseif ($st === 'terlambat') {
                                        $statusColor = 'bg-amber-50 dark:bg-amber-950/40 border-amber-300 dark:border-amber-800 text-amber-800 dark:text-amber-300 font-bold';
                                    } elseif (in_array($st, ['izin', 'cuti', 'dinas', 'sakit'])) {
                                        $statusColor = 'bg-blue-50 dark:bg-blue-950/40 border-blue-300 dark:border-blue-800 text-blue-800 dark:text-blue-300 font-bold';
                                    } else {
                                        $statusColor = 'bg-rose-50 dark:bg-rose-950/40 border-rose-300 dark:border-rose-800 text-rose-800 dark:text-rose-300 font-bold';
                                    }
                                } elseif ($dayItem['isWeekend']) {
                                    $statusColor = 'bg-slate-100 dark:bg-slate-800/80 border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500';
                                }
                            @endphp
                            <div class="p-1 sm:p-2 border rounded-lg sm:rounded-xl {{ $statusColor }} {{ $dayItem['isToday'] ? 'ring-2 ring-blue-500' : '' }} text-xs transition">
                                <div class="font-bold text-[11px] sm:text-xs">{{ $dayItem['day'] }}</div>
                                <div class="text-[9px] sm:text-[10px] mt-0.5 truncate">
                                    @if($dayItem['attendance'])
                                        {{ ucfirst($dayItem['attendance']->status) }}
                                    @elseif($dayItem['isWeekend'])
                                        Libur
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tabel Riwayat -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors duration-200">
                <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-slate-700/80 flex justify-between items-center">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Daftar Rekapitulasi Presensi</h3>
                    <span class="text-xs text-slate-400 dark:text-slate-500">Total data: {{ $attendances->total() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-xs sm:text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 sm:px-6 py-3.5">Tanggal</th>
                                <th class="px-4 sm:px-6 py-3.5">Jam Masuk</th>
                                <th class="px-4 sm:px-6 py-3.5">Jam Pulang</th>
                                <th class="px-4 sm:px-6 py-3.5">Jarak (Masuk/Pulang)</th>
                                <th class="px-4 sm:px-6 py-3.5">Status</th>
                                <th class="px-4 sm:px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            @forelse($attendances as $att)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/50 transition">
                                    <td class="px-4 sm:px-6 py-3.5 font-medium text-slate-900 dark:text-white whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($att->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 dark:text-slate-300 whitespace-nowrap font-mono">
                                        {{ $att->jam_masuk ?? '-' }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 dark:text-slate-300 whitespace-nowrap font-mono">
                                        {{ $att->jam_pulang ?? '-' }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 text-slate-500 dark:text-slate-400 whitespace-nowrap text-xs">
                                        Masuk: {{ $att->distance_masuk ? round($att->distance_masuk) . 'm' : '-' }} <br>
                                        Pulang: {{ $att->distance_pulang ? round($att->distance_pulang) . 'm' : '-' }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                        @php
                                            $badgeClasses = match($att->status) {
                                                'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/40',
                                                'terlambat' => 'bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/40',
                                                'izin' => 'bg-blue-50 text-blue-700 border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40',
                                                'sakit' => 'bg-purple-50 text-purple-700 border-purple-200/60 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/40',
                                                'dinas' => 'bg-indigo-50 text-indigo-700 border-indigo-200/60 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/40',
                                                'cuti' => 'bg-cyan-50 text-cyan-700 border-cyan-200/60 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-800/40',
                                                default => 'bg-rose-50 text-rose-700 border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/40',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold border {{ $badgeClasses }}">
                                            {{ ucfirst($att->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 text-center whitespace-nowrap">
                                        <a href="{{ route('attendance.history.show', $att->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 text-blue-700 dark:text-blue-300 text-xs font-semibold rounded-lg border border-blue-200/60 dark:border-blue-800/50 transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>Detail</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        Tidak ada catatan riwayat absensi yang ditemukan untuk filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700/80">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

        </div>
    </div>
</x-app-layout>
