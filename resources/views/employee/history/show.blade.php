<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #detail-map { height: 350px; width: 100%; border-radius: 0.75rem; z-index: 10; }
    </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Presensi') }}
            </h2>
            <a href="{{ route('attendance.history.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Riwayat
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Ringkasan Informasi Hari & Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Tanggal Absensi</span>
                        <h3 class="text-2xl font-extrabold text-gray-900">{{ $tanggalFormatted }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Hari: <span class="font-semibold text-gray-700">{{ $hari }}</span></p>
                    </div>
                    <div>
                        @php
                            $badgeClasses = match($attendance->status) {
                                'hadir' => 'bg-green-100 text-green-800 border-green-200',
                                'terlambat' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'sakit' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'dinas' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'cuti' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                default => 'bg-red-100 text-red-800 border-red-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold border {{ $badgeClasses }}">
                            Status: {{ ucfirst($attendance->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Grid Check-In dan Check-Out -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- KARTU CHECK-IN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                            <h4 class="text-base font-bold text-gray-900 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span>
                                Presensi Masuk (Check-In)
                            </h4>
                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md">
                                {{ $attendance->jam_masuk ?? 'Belum Tercatat' }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <!-- Foto Selfie Masuk -->
                            <div class="flex justify-center">
                                @if($attendance->selfie_masuk)
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $attendance->selfie_masuk) }}" alt="Selfie Masuk" class="w-48 h-48 object-cover rounded-xl shadow-md border-2 border-emerald-100 mx-auto">
                                        <span class="text-xs text-gray-500 block mt-1.5 font-medium">Foto Selfie Masuk</span>
                                    </div>
                                @else
                                    <div class="w-48 h-48 bg-gray-100 rounded-xl border border-dashed border-gray-300 flex items-center justify-center text-xs text-gray-400">
                                        Foto Tidak Tersedia
                                    </div>
                                @endif
                            </div>

                            <!-- Data GPS Masuk -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-gray-500 block">Jam Masuk</span>
                                    <span class="font-semibold text-gray-900">{{ $attendance->jam_masuk ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">Jarak dari Kantor</span>
                                    <span class="font-semibold text-gray-900">{{ $attendance->distance_masuk ? round($attendance->distance_masuk) . ' meter' : '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">Latitude</span>
                                    <span class="font-mono text-gray-800">{{ $attendance->latitude_masuk ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">Longitude</span>
                                    <span class="font-mono text-gray-800">{{ $attendance->longitude_masuk ?? '-' }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-500 block">Akurasi GPS</span>
                                    <span class="font-semibold text-gray-800">{{ $attendance->accuracy_masuk ? round($attendance->accuracy_masuk) . ' meter' : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KARTU CHECK-OUT -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                            <h4 class="text-base font-bold text-gray-900 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                Presensi Pulang (Check-Out)
                            </h4>
                            <span class="text-xs font-semibold {{ $attendance->jam_pulang ? 'text-red-700 bg-red-50' : 'text-gray-500 bg-gray-100' }} px-2.5 py-0.5 rounded-md">
                                {{ $attendance->jam_pulang ?? 'Belum Check-Out' }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <!-- Foto Selfie Pulang -->
                            <div class="flex justify-center">
                                @if($attendance->selfie_pulang)
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $attendance->selfie_pulang) }}" alt="Selfie Pulang" class="w-48 h-48 object-cover rounded-xl shadow-md border-2 border-red-100 mx-auto">
                                        <span class="text-xs text-gray-500 block mt-1.5 font-medium">Foto Selfie Pulang</span>
                                    </div>
                                @else
                                    <div class="w-48 h-48 bg-gray-100 rounded-xl border border-dashed border-gray-300 flex flex-col items-center justify-center text-xs text-gray-400 p-4 text-center">
                                        <svg class="w-8 h-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Belum melakukan absensi pulang
                                    </div>
                                @endif
                            </div>

                            <!-- Data GPS Pulang -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-gray-500 block">Jam Pulang</span>
                                    <span class="font-semibold text-gray-900">{{ $attendance->jam_pulang ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">Jarak dari Kantor</span>
                                    <span class="font-semibold text-gray-900">{{ $attendance->distance_pulang ? round($attendance->distance_pulang) . ' meter' : '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">Latitude</span>
                                    <span class="font-mono text-gray-800">{{ $attendance->latitude_pulang ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">Longitude</span>
                                    <span class="font-mono text-gray-800">{{ $attendance->longitude_pulang ?? '-' }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-500 block">Akurasi GPS</span>
                                    <span class="font-semibold text-gray-800">{{ $attendance->accuracy_pulang ? round($attendance->accuracy_pulang) . ' meter' : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Peta Lokasi Titik Absensi Leaflet + OpenStreetMap -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                    <div>
                        <h4 class="text-base font-bold text-gray-900">Peta Titik Lokasi Presensi</h4>
                        <p class="text-xs text-gray-500">Visualisasi titik koordinat kantor dan posisi presensi pegawai</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-600 mr-1.5"></span> Kantor</span>
                        @if($attendance->latitude_masuk)
                            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-1.5"></span> Masuk</span>
                        @endif
                        @if($attendance->latitude_pulang)
                            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-1.5"></span> Pulang</span>
                        @endif
                    </div>
                </div>

                <div id="detail-map" class="shadow-inner border border-gray-200"></div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if($officeLocation)
            const officeLat = {{ $officeLocation->latitude }};
            const officeLng = {{ $officeLocation->longitude }};
            const officeRadius = {{ $officeLocation->radius_meter }};
            const officeName = '{{ addslashes($officeLocation->nama) }}';
            @else
            const officeLat = -6.5683000;
            const officeLng = 107.7634000;
            const officeRadius = 100;
            const officeName = 'Kantor Dinas';
            @endif

            const map = L.map('detail-map').setView([officeLat, officeLng], 16);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const markers = [];

            // Marker Kantor & Radius
            const officeMarker = L.circleMarker([officeLat, officeLng], {
                radius: 8,
                fillColor: '#2563eb',
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            }).addTo(map);
            officeMarker.bindPopup('<b>' + officeName + '</b><br>Radius: ' + officeRadius + 'm');
            markers.push(officeMarker);

            L.circle([officeLat, officeLng], {
                color: '#2563eb',
                fillColor: '#3b82f6',
                fillOpacity: 0.15,
                radius: officeRadius
            }).addTo(map);

            // Marker Masuk
            @if($attendance->latitude_masuk && $attendance->longitude_masuk)
            const inLat = {{ $attendance->latitude_masuk }};
            const inLng = {{ $attendance->longitude_masuk }};
            const inMarker = L.circleMarker([inLat, inLng], {
                radius: 8,
                fillColor: '#10b981',
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            }).addTo(map);
            inMarker.bindPopup('<b>Lokasi Masuk</b><br>Jam: {{ $attendance->jam_masuk }}<br>Jarak: {{ round($attendance->distance_masuk ?? 0) }}m');
            markers.push(inMarker);
            @endif

            // Marker Pulang
            @if($attendance->latitude_pulang && $attendance->longitude_pulang)
            const outLat = {{ $attendance->latitude_pulang }};
            const outLng = {{ $attendance->longitude_pulang }};
            const outMarker = L.circleMarker([outLat, outLng], {
                radius: 8,
                fillColor: '#ef4444',
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            }).addTo(map);
            outMarker.bindPopup('<b>Lokasi Pulang</b><br>Jam: {{ $attendance->jam_pulang }}<br>Jarak: {{ round($attendance->distance_pulang ?? 0) }}m');
            markers.push(outMarker);
            @endif

            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.3));
            }

            setTimeout(() => {
                map.invalidateSize();
            }, 300);
        });
    </script>
    @endpush
</x-app-layout>
