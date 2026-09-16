<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Rekapitulasi Absensi PPPK') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Dinas Kearsipan dan Perpustakaan Kabupaten Subang
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if(Route::has('admin.reports.index'))
                    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg transition">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Ekspor Laporan
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filter Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.recap.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Bulan -->
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                        <select name="month" id="month" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                        <select name="year" id="year" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Unit Kerja -->
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit Kerja</label>
                        <select name="unit_id" id="unit_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pegawai -->
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PPPK</label>
                        <select name="employee_id" id="employee_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Pegawai</option>
                            @foreach($allEmployees as $emp)
                                <option value="{{ $emp->id }}" {{ $selectedEmployeeId == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Filter -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.recap.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-md transition" title="Reset Filter">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Bar -->
            <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 rounded-r-lg flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm text-blue-900 dark:text-blue-200">
                <div>
                    Periode: <span class="font-semibold">{{ $months[$selectedMonth] }} {{ $selectedYear }}</span> 
                    &bull; Estimasi Hari Kerja Efektif: <span class="font-semibold">{{ $workingDays }} hari</span>
                </div>
                <div class="mt-1 sm:mt-0 text-xs text-blue-700 dark:text-blue-300">
                    Total {{ $recapData->count() }} Pegawai PPPK ditampilkan
                </div>
            </div>

            <!-- Recap Table Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pegawai PPPK</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Kerja</th>
                                <th class="px-3 py-3 text-center text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Hadir</th>
                                <th class="px-3 py-3 text-center text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Terlambat</th>
                                <th class="px-3 py-3 text-center text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wider">Izin</th>
                                <th class="px-3 py-3 text-center text-xs font-semibold text-orange-700 dark:text-orange-400 uppercase tracking-wider">Sakit</th>
                                <th class="px-3 py-3 text-center text-xs font-semibold text-purple-700 dark:text-purple-400 uppercase tracking-wider">Cuti</th>
                                <th class="px-3 py-3 text-center text-xs font-semibold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider">Dinas</th>
                                <th class="px-3 py-3 text-center text-xs font-semibold text-rose-700 dark:text-rose-400 uppercase tracking-wider">Alpha</th>
                                <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recapData as $index => $item)
                                @php
                                    $attendanceRate = $workingDays > 0 ? round((($item->hadir + $item->terlambat) / $workingDays) * 100) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $item->employee->nama }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">NIP: {{ $item->employee->nip ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $item->employee->unit->nama ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3.5 whitespace-nowrap text-center text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                        {{ $item->hadir }}
                                    </td>
                                    <td class="px-3 py-3.5 whitespace-nowrap text-center text-sm font-semibold text-amber-600 dark:text-amber-400">
                                        {{ $item->terlambat }}
                                    </td>
                                    <td class="px-3 py-3.5 whitespace-nowrap text-center text-sm font-medium text-blue-600 dark:text-blue-400">
                                        {{ $item->izin }}
                                    </td>
                                    <td class="px-3 py-3.5 whitespace-nowrap text-center text-sm font-medium text-orange-600 dark:text-orange-400">
                                        {{ $item->sakit }}
                                    </td>
                                    <td class="px-3 py-3.5 whitespace-nowrap text-center text-sm font-medium text-purple-600 dark:text-purple-400">
                                        {{ $item->cuti }}
                                    </td>
                                    <td class="px-3 py-3.5 whitespace-nowrap text-center text-sm font-medium text-cyan-600 dark:text-cyan-400">
                                        {{ $item->dinas }}
                                    </td>
                                    <td class="px-3 py-3.5 whitespace-nowrap text-center text-sm font-semibold text-rose-600 dark:text-rose-400">
                                        {{ $item->alpha }}
                                    </td>
                                    <td class="px-3 py-3.5 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $attendanceRate >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : ($attendanceRate >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300') }}">
                                            {{ $attendanceRate }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        Tidak ada data pegawai yang ditemukan untuk filter yang dipilih.
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
