<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Absensi') }}
            </h2>
            <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Lakukan Absensi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <form method="GET" action="{{ route('attendance.history.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="bulan" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Bulan</label>
                        <select name="bulan" id="bulan" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all" {{ $selectedMonth === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @foreach($daftarBulan as $num => $nama)
                                <option value="{{ $num }}" {{ (string)$selectedMonth === (string)$num ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tahun" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tahun</label>
                        <select name="tahun" id="tahun" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @foreach($daftarTahun as $thn)
                                <option value="{{ $thn }}" {{ (string)$selectedYear === (string)$thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status Kehadiran</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Terapkan
                        </button>
                        <a href="{{ route('attendance.history.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Visualisasi Kalender Ringkas (Jika Bulan dan Tahun dipilih) -->
            @if(!empty($calendarDays))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                            Kalender Kehadiran: {{ $daftarBulan[(int)$selectedMonth] ?? '' }} {{ $selectedYear }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-1"></span> Hadir</span>
                            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-yellow-500 mr-1"></span> Terlambat</span>
                            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-1"></span> Izin/Cuti/Dinas</span>
                            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-gray-300 mr-1"></span> Kosong</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 gap-2 text-center">
                        @foreach($calendarDays as $dayItem)
                            @php
                                $statusColor = 'bg-gray-50 border-gray-200 text-gray-600';
                                if ($dayItem['attendance']) {
                                    $st = $dayItem['attendance']->status;
                                    if ($st === 'hadir') {
                                        $statusColor = 'bg-green-50 border-green-300 text-green-800 font-bold';
                                    } elseif ($st === 'terlambat') {
                                        $statusColor = 'bg-yellow-50 border-yellow-300 text-yellow-800 font-bold';
                                    } elseif (in_array($st, ['izin', 'cuti', 'dinas', 'sakit'])) {
                                        $statusColor = 'bg-blue-50 border-blue-300 text-blue-800 font-bold';
                                    } else {
                                        $statusColor = 'bg-red-50 border-red-300 text-red-800 font-bold';
                                    }
                                } elseif ($dayItem['isWeekend']) {
                                    $statusColor = 'bg-gray-100 border-gray-200 text-gray-400';
                                }
                            @endphp
                            <div class="p-2 border rounded-lg {{ $statusColor }} {{ $dayItem['isToday'] ? 'ring-2 ring-indigo-500' : '' }} text-xs transition">
                                <div class="font-semibold">{{ $dayItem['day'] }}</div>
                                <div class="text-[10px] mt-0.5 truncate">
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-base font-bold text-gray-900">Daftar Rekapitulasi Presensi</h3>
                    <span class="text-xs text-gray-500">Total data: {{ $attendances->total() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Tanggal</th>
                                <th class="px-6 py-3.5">Jam Masuk</th>
                                <th class="px-6 py-3.5">Jam Pulang</th>
                                <th class="px-6 py-3.5">Jarak (Masuk/Pulang)</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($attendances as $att)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($att->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                        {{ $att->jam_masuk ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                        {{ $att->jam_pulang ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 whitespace-nowrap text-xs">
                                        Masuk: {{ $att->distance_masuk ? round($att->distance_masuk) . 'm' : '-' }} <br>
                                        Pulang: {{ $att->distance_pulang ? round($att->distance_pulang) . 'm' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeClasses = match($att->status) {
                                                'hadir' => 'bg-green-100 text-green-800',
                                                'terlambat' => 'bg-yellow-100 text-yellow-800',
                                                'izin' => 'bg-blue-100 text-blue-800',
                                                'sakit' => 'bg-purple-100 text-purple-800',
                                                'dinas' => 'bg-indigo-100 text-indigo-800',
                                                'cuti' => 'bg-cyan-100 text-cyan-800',
                                                default => 'bg-red-100 text-red-800',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                                            {{ ucfirst($att->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <a href="{{ route('attendance.history.show', $att->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-md border border-indigo-200 transition">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada catatan riwayat absensi yang ditemukan untuk filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
