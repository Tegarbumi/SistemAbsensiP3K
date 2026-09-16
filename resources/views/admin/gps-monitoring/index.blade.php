<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #gpsLiveMap { height: 560px; border-radius: 0.75rem; z-index: 10; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Monitoring GPS Presensi PPPK') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Dinas Kearsipan dan Perpustakaan Kabupaten Subang &bull; {{ $formattedDate }}</p>
            </div>
            <a href="{{ route('admin.attendance-monitoring.index', ['tanggal' => $selectedDate]) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                Tabel Absensi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Panel -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-5">
                <form method="GET" action="{{ route('admin.gps-monitoring.index') }}" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ $selectedDate }}" class="rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="unit_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Unit Kerja</label>
                        <select name="unit_id" id="unit_id" class="rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 min-w-[200px]">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ (string)request('unit_id') === (string)$unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('admin.gps-monitoring.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition text-center">
                            Hari Ini
                        </a>
                    </div>

                    <!-- Indikator Legend -->
                    <div class="ml-auto flex items-center space-x-4 text-xs font-medium text-gray-600">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span>
                            <span>Titik Kantor ({{ count($officeLocations) }})</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                            <span>Check-In</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span>
                            <span>Check-Out</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Peta GPS Interaktif -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <div class="mb-3 flex justify-between items-center">
                    <p class="text-xs text-gray-500">
                        Klik pada marker kantor atau pegawai untuk melihat detail foto, jarak, dan waktu absensi.
                    </p>
                    <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full">
                        Total Titik Terdeteksi: {{ count($markers) }}
                    </span>
                </div>

                <div id="gpsLiveMap" class="w-full border border-gray-300 shadow-inner"></div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const offices = @json($officeLocations);
                const markers = @json($markers);

                let defaultLat = -6.5683;
                let defaultLng = 107.7634;

                if (offices.length > 0) {
                    defaultLat = parseFloat(offices[0].latitude);
                    defaultLng = parseFloat(offices[0].longitude);
                }

                const map = L.map('gpsLiveMap').setView([defaultLat, defaultLng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const allBounds = [];

                // Render Titik Kantor
                offices.forEach(office => {
                    const lat = parseFloat(office.latitude);
                    const lng = parseFloat(office.longitude);
                    const radius = parseInt(office.radius_meter) || 100;

                    allBounds.push([lat, lng]);

                    const marker = L.marker([lat, lng]).addTo(map);
                    marker.bindPopup(`
                        <div class="text-xs">
                            <strong class="text-sm font-bold text-gray-900">${office.nama}</strong><br>
                            <span class="text-gray-500">${office.alamat || '-'}</span><br>
                            <span class="inline-block mt-1 font-semibold text-blue-700">Radius Batas Absensi: ${radius} meter</span>
                        </div>
                    `);

                    L.circle([lat, lng], {
                        color: '#2563eb',
                        fillColor: '#3b82f6',
                        fillOpacity: 0.15,
                        radius: radius
                    }).addTo(map);
                });

                // Render Titik Presensi Pegawai PPPK
                markers.forEach(item => {
                    const lat = parseFloat(item.lat);
                    const lng = parseFloat(item.lng);
                    const isMasuk = item.type === 'masuk';

                    allBounds.push([lat, lng]);

                    const markerColor = isMasuk ? '#059669' : '#7c3aed';
                    const fillColor = isMasuk ? '#10b981' : '#8b5cf6';

                    const circleMarker = L.circleMarker([lat, lng], {
                        color: markerColor,
                        fillColor: fillColor,
                        fillOpacity: 0.85,
                        radius: 8,
                        weight: 2
                    }).addTo(map);

                    let photoHtml = '';
                    if (item.selfie) {
                        photoHtml = `<div class="mb-2"><img src="${item.selfie}" class="w-20 h-20 rounded-lg object-cover border border-gray-300 shadow-xs" alt="Selfie"></div>`;
                    }

                    const popupContent = `
                        <div class="text-xs max-w-xs">
                            ${photoHtml}
                            <strong class="text-sm font-bold text-gray-900 block">${item.nama}</strong>
                            <div class="text-gray-500 font-mono text-[11px] mb-1">NIP: ${item.nip}</div>
                            <div>Unit: <span class="font-medium">${item.unit}</span></div>
                            <div>Tipe: <span class="font-bold ${isMasuk ? 'text-emerald-700' : 'text-purple-700'}">${isMasuk ? 'Check-In' : 'Check-Out'}</span></div>
                            <div>Waktu: <span class="font-semibold">${item.waktu} WIB</span></div>
                            <div>Status: <span class="font-semibold capitalize">${item.status}</span></div>
                            <div>Jarak Kantor: <span class="font-bold text-gray-800">${item.jarak} meter</span></div>
                            <div class="mt-2 pt-1 border-t border-gray-200">
                                <a href="/admin/absensi/${item.attendance_id}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                    Lihat Detail Lengkap &rarr;
                                </a>
                            </div>
                        </div>
                    `;

                    circleMarker.bindPopup(popupContent);
                });

                if (allBounds.length > 1) {
                    map.fitBounds(allBounds, { padding: [40, 40] });
                }
            });
        </script>
    @endpush
</x-app-layout>
