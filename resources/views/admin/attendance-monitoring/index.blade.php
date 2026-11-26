<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col xs:flex-row justify-between items-start xs:items-center gap-3">
            <div>
                <h2 class="font-bold text-lg text-slate-900 dark:text-white tracking-tight">
                    {{ __('Monitoring Presensi PPPK') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dinas Kearsipan dan Perpustakaan Kabupaten Subang &bull; {{ $formattedDate }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ Route::has('admin.gps-monitoring.index') ? route('admin.gps-monitoring.index') : url('/admin/monitoring-gps') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Peta GPS Live</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-5">

            <!-- Kartu Ringkasan Kehadiran Hari Terpilih -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase block mb-1">Total Data</span>
                    <span class="text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ $summary['total'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Pegawai</span>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-emerald-300">
                    <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase block mb-1">Hadir</span>
                    <span class="text-2xl font-bold text-emerald-700 dark:text-emerald-400 tabular-nums">{{ $summary['hadir'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Tepat Waktu</span>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-amber-300">
                    <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase block mb-1">Terlambat</span>
                    <span class="text-2xl font-bold text-amber-600 dark:text-amber-400 tabular-nums">{{ $summary['terlambat'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Melebihi Jadwal</span>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-blue-300">
                    <span class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase block mb-1">Izin</span>
                    <span class="text-2xl font-bold text-blue-700 dark:text-blue-400 tabular-nums">{{ $summary['izin'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Disetujui</span>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-purple-300">
                    <span class="text-xs font-semibold text-purple-700 dark:text-purple-400 uppercase block mb-1">Sakit</span>
                    <span class="text-2xl font-bold text-purple-700 dark:text-purple-400 tabular-nums">{{ $summary['sakit'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Surat Dokter</span>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-indigo-300">
                    <span class="text-xs font-semibold text-indigo-700 dark:text-indigo-400 uppercase block mb-1">Dinas / Cuti</span>
                    <span class="text-2xl font-bold text-indigo-700 dark:text-indigo-400 tabular-nums">{{ $summary['dinas'] + $summary['cuti'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Tugas Luar</span>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center col-span-2 sm:col-span-1 transition hover:border-rose-300">
                    <span class="text-xs font-semibold text-rose-700 dark:text-rose-400 uppercase block mb-1">Alpha</span>
                    <span class="text-2xl font-bold text-rose-700 dark:text-rose-400 tabular-nums">{{ $summary['alpha'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-0.5">Tanpa Keterangan</span>
                </div>
            </div>

            {{-- Form Filter Pencarian --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-5 shadow-xs transition-colors duration-200">
                <form method="GET" action="{{ route('admin.attendance-monitoring.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ $selectedDate }}" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div>
                        <label for="search" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Cari PPPK</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama / NIP..." class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div>
                        <label for="unit_id" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Unit Kerja</label>
                        <select name="unit_id" id="unit_id" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ (string)request('unit_id') === (string)$unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            <option value="all">Semua Status</option>
                            <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="terlambat" {{ request('status') === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="dinas" {{ request('status') === 'dinas' ? 'selected' : '' }}>Dinas</option>
                            <option value="cuti" {{ request('status') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="alpha" {{ request('status') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.attendance-monitoring.index') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-lg transition text-center border border-slate-200 dark:border-slate-600">
                            Hari Ini
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabel Data Absensi --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors duration-200">
                <div class="overflow-x-auto -webkit-overflow-scrolling-touch">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-xs sm:text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-800/90 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 font-bold">Pegawai PPPK</th>
                                <th scope="col" class="px-5 py-3.5 font-bold">Unit Kerja</th>
                                <th scope="col" class="px-5 py-3.5 text-center font-bold">Masuk</th>
                                <th scope="col" class="px-5 py-3.5 text-center font-bold">Pulang</th>
                                <th scope="col" class="px-5 py-3.5 text-center font-bold">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-center font-bold">Jarak GPS</th>
                                <th scope="col" class="px-5 py-3.5 text-right font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                            @forelse($attendances as $item)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $item->employee?->nama ?: 'Pegawai #' . $item->employee_id }}</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">NIP: {{ $item->employee?->nip ?: '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">
                                            {{ $item->unit?->nama ?: ($item->employee?->unit?->nama ?: '-') }}
                                        </span>
                                        @if($item->unit_id && $item->employee?->unit_id && $item->unit_id !== $item->employee->unit_id)
                                            <div class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold mt-0.5">
                                                (Induk: {{ $item->employee->unit?->nama }})
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                        @if($item->jam_masuk)
                                            <div class="inline-flex items-center gap-2">
                                                @if($item->selfie_masuk)
                                                    <img src="{{ asset('storage/' . $item->selfie_masuk) }}" alt="Selfie Masuk" class="w-7 h-7 rounded-md object-cover border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                @endif
                                                <span class="font-bold text-slate-900 dark:text-white font-mono text-xs">{{ substr($item->jam_masuk, 0, 5) }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                        @if($item->jam_pulang)
                                            <div class="inline-flex items-center gap-2">
                                                @if($item->selfie_pulang)
                                                    <img src="{{ asset('storage/' . $item->selfie_pulang) }}" alt="Selfie Pulang" class="w-7 h-7 rounded-md object-cover border border-slate-200 dark:border-slate-700 shadow-2xs">
                                                @endif
                                                <span class="font-bold text-slate-900 dark:text-white font-mono text-xs">{{ substr($item->jam_pulang, 0, 5) }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">Belum Pulang</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                        @php
                                            $badgeColors = match($item->status) {
                                                'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
                                                'terlambat' => 'bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60',
                                                'izin' => 'bg-blue-50 text-blue-700 border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/60',
                                                'sakit' => 'bg-purple-50 text-purple-700 border-purple-200/60 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800/60',
                                                'dinas' => 'bg-indigo-50 text-indigo-700 border-indigo-200/60 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800/60',
                                                'cuti' => 'bg-cyan-50 text-cyan-700 border-cyan-200/60 dark:bg-cyan-950/40 dark:text-cyan-300 dark:border-cyan-800/60',
                                                default => 'bg-rose-50 text-rose-700 border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeColors }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center text-xs">
                                        @if($item->distance_masuk !== null)
                                            <span class="font-mono text-slate-700 dark:text-slate-300 font-medium">{{ round($item->distance_masuk) }} m</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-xs font-medium">
                                        <a href="{{ route('admin.attendance-monitoring.show', $item) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800/50 rounded-lg transition font-semibold shadow-2xs">
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
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                        Tidak ada catatan absensi pada tanggal {{ $formattedDate }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>