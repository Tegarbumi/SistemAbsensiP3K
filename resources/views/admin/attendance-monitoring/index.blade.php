<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Monitoring Absensi PPPK') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Dinas Kearsipan dan Perpustakaan Kabupaten Subang &bull; {{ $formattedDate }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ Route::has('admin.gps-monitoring.index') ? route('admin.gps-monitoring.index') : url('/admin/monitoring-gps') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Peta GPS Live
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Kartu Ringkasan Kehadiran Hari Terpilih -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <span class="text-xs text-gray-500 block">Total Data</span>
                    <span class="text-xl font-black text-gray-900">{{ $summary['total'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-green-200 shadow-sm text-center bg-green-50/30">
                    <span class="text-xs text-green-700 font-semibold block">Hadir</span>
                    <span class="text-xl font-black text-green-700">{{ $summary['hadir'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-yellow-200 shadow-sm text-center bg-yellow-50/30">
                    <span class="text-xs text-yellow-700 font-semibold block">Terlambat</span>
                    <span class="text-xl font-black text-yellow-700">{{ $summary['terlambat'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-sm text-center bg-blue-50/30">
                    <span class="text-xs text-blue-700 font-semibold block">Izin</span>
                    <span class="text-xl font-black text-blue-700">{{ $summary['izin'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-purple-200 shadow-sm text-center bg-purple-50/30">
                    <span class="text-xs text-purple-700 font-semibold block">Sakit</span>
                    <span class="text-xl font-black text-purple-700">{{ $summary['sakit'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-indigo-200 shadow-sm text-center bg-indigo-50/30">
                    <span class="text-xs text-indigo-700 font-semibold block">Dinas / Cuti</span>
                    <span class="text-xl font-black text-indigo-700">{{ $summary['dinas'] + $summary['cuti'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-red-200 shadow-sm text-center bg-red-50/30 col-span-2 sm:col-span-1">
                    <span class="text-xs text-red-700 font-semibold block">Alpha</span>
                    <span class="text-xl font-black text-red-700">{{ $summary['alpha'] }}</span>
                </div>
            </div>

            <!-- Form Filter Pencarian -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <form method="GET" action="{{ route('admin.attendance-monitoring.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ $selectedDate }}" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cari PPPK</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama / NIP..." class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="unit_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Unit Kerja</label>
                        <select name="unit_id" id="unit_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ (string)request('unit_id') === (string)$unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Terapkan
                        </button>
                        <a href="{{ route('admin.attendance-monitoring.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition text-center">
                            Hari Ini
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Absensi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Pegawai PPPK</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Unit Kerja</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Masuk & Selfie</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Pulang & Selfie</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Status</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Jarak GPS</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($attendances as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900">{{ $item->employee?->nama ?: 'Pegawai #' . $item->employee_id }}</div>
                                        <div class="text-xs text-gray-500 font-mono">NIP: {{ $item->employee?->nip ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs text-gray-700 font-medium">
                                            {{ $item->employee?->unit?->nama ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($item->jam_masuk)
                                            <div class="flex items-center justify-center space-x-2">
                                                @if($item->selfie_masuk)
                                                    <img src="{{ asset('storage/' . $item->selfie_masuk) }}" alt="Selfie Masuk" class="w-8 h-8 rounded-full object-cover border border-gray-300 shadow-xs">
                                                @endif
                                                <span class="font-bold text-gray-800 text-xs">{{ substr($item->jam_masuk, 0, 5) }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($item->jam_pulang)
                                            <div class="flex items-center justify-center space-x-2">
                                                @if($item->selfie_pulang)
                                                    <img src="{{ asset('storage/' . $item->selfie_pulang) }}" alt="Selfie Pulang" class="w-8 h-8 rounded-full object-cover border border-gray-300 shadow-xs">
                                                @endif
                                                <span class="font-bold text-gray-800 text-xs">{{ substr($item->jam_pulang, 0, 5) }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-amber-600 font-medium">Belum Pulang</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $badgeColors = match($item->status) {
                                                'hadir' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'terlambat' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'sakit' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                'dinas' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                'cuti' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                                default => 'bg-red-100 text-red-800 border-red-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeColors }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                        @if($item->distance_masuk !== null)
                                            <span class="font-mono text-gray-700">{{ round($item->distance_masuk) }} m</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        <a href="{{ route('admin.attendance-monitoring.show', $item) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition font-semibold">
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
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada catatan absensi pada tanggal {{ $formattedDate }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($attendances->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $attendances->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
