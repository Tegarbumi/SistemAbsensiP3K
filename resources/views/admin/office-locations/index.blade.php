<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #overviewMap { height: 360px; border-radius: 0.75rem; z-index: 10; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Kelola Lokasi Kantor Absensi') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Dinas Kearsipan dan Perpustakaan Kabupaten Subang</p>
            </div>
            <a href="{{ route('admin.office-locations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Lokasi Kantor
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Peta Visual Semua Titik Kantor -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Peta Sebaran Titik Kantor & Radius Absensi</h3>
                        <p class="text-xs text-gray-500">Menampilkan seluruh titik kantor aktif beserta radius batas toleransi absensi.</p>
                    </div>
                </div>
                <div id="overviewMap" class="w-full border border-gray-300"></div>
            </div>

            <!-- Filter & Pencarian -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <form method="GET" action="{{ route('admin.office-locations.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cari Lokasi</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik nama atau alamat..." class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="unit_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Unit / Bagian</label>
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
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Cari
                        </button>
                        <a href="{{ route('admin.office-locations.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Lokasi Kantor -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Nama Lokasi</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Unit Terkait</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Koordinat GPS</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Radius</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Status</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($locations as $location)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $location->nama }}</div>
                                        <div class="text-xs text-gray-500 max-w-xs truncate">{{ $location->alamat ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($location->unit)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                                {{ $location->unit->nama }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">Umum / Semua Unit</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-600">
                                        <div>Lat: {{ number_format($location->latitude, 6) }}</div>
                                        <div>Lng: {{ number_format($location->longitude, 6) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            {{ $location->radius_meter }} meter
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($location->status)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- Toggle Status -->
                                            <form action="{{ route('admin.office-locations.toggle-status', $location) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-1.5 rounded-lg border {{ $location->status ? 'border-amber-300 text-amber-700 hover:bg-amber-50' : 'border-emerald-300 text-emerald-700 hover:bg-emerald-50' }} transition" title="{{ $location->status ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    @if($location->status)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.office-locations.edit', $location) }}" class="p-1.5 rounded-lg border border-indigo-300 text-indigo-700 hover:bg-indigo-50 transition" title="Edit Lokasi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>

                                            <!-- Hapus -->
                                            <form action="{{ route('admin.office-locations.destroy', $location) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi kantor ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition" title="Hapus Lokasi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada data lokasi kantor ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($locations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $locations->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const locationsData = @json($locations->items());

                // Default Subang coordinates
                let centerLat = -6.5683;
                let centerLng = 107.7634;

                if (locationsData.length > 0) {
                    centerLat = parseFloat(locationsData[0].latitude);
                    centerLng = parseFloat(locationsData[0].longitude);
                }

                const map = L.map('overviewMap').setView([centerLat, centerLng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const bounds = [];

                locationsData.forEach(loc => {
                    const lat = parseFloat(loc.latitude);
                    const lng = parseFloat(loc.longitude);
                    const radius = parseInt(loc.radius_meter) || 100;
                    const isActive = Boolean(loc.status);

                    bounds.push([lat, lng]);

                    const marker = L.marker([lat, lng]).addTo(map);
                    marker.bindPopup(`
                        <div class="text-xs">
                            <strong class="text-sm font-semibold">${loc.nama}</strong><br>
                            ${loc.alamat || ''}<br>
                            <span class="inline-block mt-1 font-mono text-gray-600">Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}</span><br>
                            <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-white ${isActive ? 'bg-emerald-600' : 'bg-red-600'}">
                                ${isActive ? 'Aktif' : 'Nonaktif'} (Radius: ${radius}m)
                            </span>
                        </div>
                    `);

                    L.circle([lat, lng], {
                        color: isActive ? '#059669' : '#dc2626',
                        fillColor: isActive ? '#10b981' : '#ef4444',
                        fillOpacity: 0.15,
                        radius: radius
                    }).addTo(map);
                });

                if (bounds.length > 1) {
                    map.fitBounds(bounds, { padding: [30, 30] });
                }
            });
        </script>
    @endpush
</x-app-layout>
