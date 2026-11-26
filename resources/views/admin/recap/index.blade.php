<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight">
                    {{ __('Rekapitulasi Absensi PPPK') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dinas Kearsipan dan Perpustakaan Kabupaten Subang</p>
            </div>
            @if(Route::has('admin.reports.index'))
                <a href="{{ route('admin.reports.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition shrink-0">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Ekspor Laporan</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-5">

            {{-- Filter Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-5 shadow-xs transition-colors duration-200">
                <form method="GET" action="{{ route('admin.recap.index') }}"
                      class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-5 gap-4 items-end">

                    {{-- Bulan --}}
                    <div>
                        <label for="month" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Bulan</label>
                        <select name="month" id="month"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <label for="year" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Tahun</label>
                        <select name="year" id="year"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Unit Kerja --}}
                    <div>
                        <label for="unit_id" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Unit Kerja</label>
                        <select name="unit_id" id="unit_id"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pegawai --}}
                    <div>
                        <label for="employee_id" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Pegawai</label>
                        <select name="employee_id" id="employee_id"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua Pegawai</option>
                            @foreach($allEmployees as $emp)
                                <option value="{{ $emp->id }}" {{ $selectedEmployeeId == $emp->id ? 'selected' : '' }}>{{ $emp->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="flex-1 inline-flex justify-center items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.recap.index') }}"
                           class="px-3.5 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl transition text-center border border-slate-200 dark:border-slate-600"
                           title="Reset Filter">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Summary Info Bar --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200/60 dark:border-blue-800/50 rounded-2xl px-4 py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-sm text-blue-900 dark:text-blue-200">
                <div class="text-xs sm:text-sm">
                    Periode: <span class="font-semibold">{{ $months[$selectedMonth] }} {{ $selectedYear }}</span>
                    &bull; Estimasi hari kerja efektif: <span class="font-semibold">{{ $workingDays }} hari</span>
                </div>
                <div class="text-xs text-blue-600 dark:text-blue-300 font-medium shrink-0">
                    {{ $recapData->count() }} Pegawai ditampilkan
                </div>
            </div>

            {{-- Recap Table --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors duration-200">
                <div class="overflow-x-auto -webkit-overflow-scrolling-touch">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-800/90">
                            <tr>
                                <th class="px-3 sm:px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">No</th>
                                <th class="px-3 sm:px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pegawai PPPK</th>
                                <th class="px-3 sm:px-4 py-3.5 text-left text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Unit</th>
                                <th class="px-2 sm:px-3 py-3.5 text-center text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Hadir</th>
                                <th class="px-2 sm:px-3 py-3.5 text-center text-[11px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Lambat</th>
                                <th class="px-2 sm:px-3 py-3.5 text-center text-[11px] font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">Izin</th>
                                <th class="px-2 sm:px-3 py-3.5 text-center text-[11px] font-bold text-orange-700 dark:text-orange-400 uppercase tracking-wider">Sakit</th>
                                <th class="px-2 sm:px-3 py-3.5 text-center text-[11px] font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider">Cuti</th>
                                <th class="px-2 sm:px-3 py-3.5 text-center text-[11px] font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider">Dinas</th>
                                <th class="px-2 sm:px-3 py-3.5 text-center text-[11px] font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider">Alpha</th>
                                <th class="px-2 sm:px-3 py-3.5 text-center text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Tingkat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                            @forelse($recapData as $index => $item)
                                @php
                                    $attendanceRate = $workingDays > 0 ? round((($item->hadir + $item->terlambat) / $workingDays) * 100) : 0;
                                    $rateColor = $attendanceRate >= 80
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60'
                                        : ($attendanceRate >= 50
                                            ? 'bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60'
                                            : 'bg-rose-50 text-rose-700 border-rose-200/60 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/60');
                                @endphp
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition">
                                    <td class="px-3 sm:px-4 py-3.5 whitespace-nowrap text-xs text-slate-400 dark:text-slate-500 tabular-nums">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-3 sm:px-4 py-3.5 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white text-xs">{{ $item->employee->nama }}</div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 font-mono mt-0.5">{{ $item->employee->nip ?? '-' }}</div>
                                    </td>
                                    <td class="px-3 sm:px-4 py-3.5 whitespace-nowrap text-xs text-slate-600 dark:text-slate-300">
                                        {{ $item->employee->unit->nama ?? '-' }}
                                    </td>
                                    <td class="px-2 sm:px-3 py-3.5 whitespace-nowrap text-center text-xs font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $item->hadir }}</td>
                                    <td class="px-2 sm:px-3 py-3.5 whitespace-nowrap text-center text-xs font-bold text-amber-600 dark:text-amber-400 tabular-nums">{{ $item->terlambat }}</td>
                                    <td class="px-2 sm:px-3 py-3.5 whitespace-nowrap text-center text-xs font-semibold text-blue-600 dark:text-blue-400 tabular-nums">{{ $item->izin }}</td>
                                    <td class="px-2 sm:px-3 py-3.5 whitespace-nowrap text-center text-xs font-semibold text-orange-600 dark:text-orange-400 tabular-nums">{{ $item->sakit }}</td>
                                    <td class="px-2 sm:px-3 py-3.5 whitespace-nowrap text-center text-xs font-semibold text-purple-600 dark:text-purple-400 tabular-nums">{{ $item->cuti }}</td>
                                    <td class="px-2 sm:px-3 py-3.5 whitespace-nowrap text-center text-xs font-semibold text-cyan-600 dark:text-cyan-400 tabular-nums">{{ $item->dinas }}</td>
                                    <td class="px-2 sm:px-3 py-3.5 whitespace-nowrap text-center text-xs font-bold text-rose-600 dark:text-rose-400 tabular-nums">{{ $item->alpha }}</td>
                                    <td class="px-2 sm:px-3 py-3.5 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $rateColor }}">
                                            {{ $attendanceRate }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tidak ada data ditemukan</p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500">Coba ubah filter bulan, tahun, atau unit kerja.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
