<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #adminDetailMap { height: 380px; border-radius: 0.75rem; z-index: 10; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Detail Catatan Presensi PPPK') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">{{ $employee?->nama }} &bull; {{ $tanggalFormatted }}</p>
            </div>
            <a href="{{ route('admin.attendance-monitoring.index', ['tanggal' => $attendance->tanggal]) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Monitoring
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card Profil & Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-lg">
                            {{ substr($employee?->nama ?: 'P', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $employee?->nama }}</h3>
                            <div class="text-xs text-gray-500">
                                <span>NIP: {{ $employee?->nip ?: '-' }}</span> &bull;
                                <span>Jabatan: {{ $employee?->jabatan ?: '-' }}</span> &bull;
                                <span>Unit: {{ $employee?->unit?->nama ?: '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        @php
                            $badgeColors = match($attendance->status) {
                                'hadir' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'terlambat' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'izin' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'sakit' => 'bg-purple-100 text-purple-800 border-purple-300',
                                'dinas' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                                'cuti' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
                                default => 'bg-red-100 text-red-800 border-red-300',
                            };
                        @endphp
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold border {{ $badgeColors }}">
                            Status: {{ ucfirst($attendance->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Grid Check-In & Check-Out Detail -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- KARTU CHECK-IN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                    <h4 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block mr-2"></span>
                        Absensi Masuk (Check-In)
                    </h4>

                    @if($attendance->jam_masuk)
                        <div class="flex flex-col sm:flex-row gap-4 items-start">
                            <div class="w-32 h-32 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                @if($attendance->selfie_masuk)
                                    <img src="{{ asset('storage/' . $attendance->selfie_masuk) }}" alt="Selfie Masuk" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">Tidak ada foto</div>
                                @endif
                            </div>

                            <div class="space-y-2 text-xs text-gray-600 flex-1">
                                <div>
                                    <span class="text-gray-400 block">Waktu Masuk:</span>
                                    <span class="text-base font-bold text-gray-900">{{ $attendance->jam_masuk }} WIB</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block">Koordinat GPS:</span>
                                    <span class="font-mono text-gray-800">{{ $attendance->latitude_masuk }}, {{ $attendance->longitude_masuk }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <div>
                                        <span class="text-gray-400 block">Akurasi GPS:</span>
                                        <span class="font-semibold text-gray-800">{{ $attendance->accuracy_masuk ? round($attendance->accuracy_masuk) . ' m' : '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block">Jarak ke Kantor:</span>
                                        <span class="font-semibold text-emerald-700">{{ $attendance->distance_masuk !== null ? round($attendance->distance_masuk) . ' m' : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-gray-400 italic py-6 text-center">Belum melakukan absensi masuk.</div>
                    @endif
                </div>

                <!-- KARTU CHECK-OUT -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                    <h4 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-purple-500 inline-block mr-2"></span>
                        Absensi Pulang (Check-Out)
                    </h4>

                    @if($attendance->jam_pulang)
                        <div class="flex flex-col sm:flex-row gap-4 items-start">
                            <div class="w-32 h-32 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                @if($attendance->selfie_pulang)
                                    <img src="{{ asset('storage/' . $attendance->selfie_pulang) }}" alt="Selfie Pulang" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">Tidak ada foto</div>
                                @endif
                            </div>

                            <div class="space-y-2 text-xs text-gray-600 flex-1">
                                <div>
                                    <span class="text-gray-400 block">Waktu Pulang:</span>
                                    <span class="text-base font-bold text-gray-900">{{ $attendance->jam_pulang }} WIB</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block">Koordinat GPS:</span>
                                    <span class="font-mono text-gray-800">{{ $attendance->latitude_pulang }}, {{ $attendance->longitude_pulang }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <div>
                                        <span class="text-gray-400 block">Akurasi GPS:</span>
                                        <span class="font-semibold text-gray-800">{{ $attendance->accuracy_pulang ? round($attendance->accuracy_pulang) . ' m' : '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block">Jarak ke Kantor:</span>
                                        <span class="font-semibold text-purple-700">{{ $attendance->distance_pulang !== null ? round($attendance->distance_pulang) . ' m' : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-gray-400 italic py-6 text-center">Belum melakukan absensi pulang.</div>
                    @endif
                </div>

            </div>

            <!-- PETA TITIK LOKASI ABSENSI -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <h4 class="text-base font-bold text-gray-900 mb-2">Peta Lokasi & Titik Koordinat Presensi</h4>
                <p class="text-xs text-gray-500 mb-4">Menampilkan titik lokasi kantor, batas radius toleransi, dan titik presensi pegawai.</p>
                <div id="adminDetailMap" class="w-full border border-gray-300"></div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const officeLat = {{ $officeLocation ? $officeLocation->latitude : -6.5683 }};
                const officeLng = {{ $officeLocation ? $officeLocation->longitude : 107.7634 }};
                const officeRadius = {{ $officeLocation ? $officeLocation->radius_meter : 100 }};
                const officeName = "{{ $officeLocation ? $officeLocation->nama : 'Kantor Dinas' }}";

                const inLat = {{ $attendance->latitude_masuk ?: 'null' }};
                const inLng = {{ $attendance->longitude_masuk ?: 'null' }};
                const outLat = {{ $attendance->latitude_pulang ?: 'null' }};
                const outLng = {{ $attendance->longitude_pulang ?: 'null' }};

                const map = L.map('adminDetailMap').setView([officeLat, officeLng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Marker Kantor & Radius
                const officeMarker = L.marker([officeLat, officeLng]).addTo(map);
                officeMarker.bindPopup(`<strong>${officeName}</strong><br>Radius: ${officeRadius} meter`).openPopup();

                L.circle([officeLat, officeLng], {
                    color: '#2563eb',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.15,
                    radius: officeRadius
                }).addTo(map);

                const bounds = [[officeLat, officeLng]];

                // Marker Masuk
                if (inLat && inLng) {
                    bounds.push([inLat, inLng]);
                    const inMarker = L.circleMarker([inLat, inLng], {
                        color: '#059669',
                        fillColor: '#10b981',
                        fillOpacity: 0.8,
                        radius: 8
                    }).addTo(map);
                    inMarker.bindPopup(`<strong>Presensi Masuk</strong><br>Waktu: {{ $attendance->jam_masuk }} WIB<br>Jarak: {{ round($attendance->distance_masuk) }}m`);
                }

                // Marker Pulang
                if (outLat && outLng) {
                    bounds.push([outLat, outLng]);
                    const outMarker = L.circleMarker([outLat, outLng], {
                        color: '#7c3aed',
                        fillColor: '#8b5cf6',
                        fillOpacity: 0.8,
                        radius: 8
                    }).addTo(map);
                    outMarker.bindPopup(`<strong>Presensi Pulang</strong><br>Waktu: {{ $attendance->jam_pulang }} WIB<br>Jarak: {{ round($attendance->distance_pulang) }}m`);
                }

                if (bounds.length > 1) {
                    map.fitBounds(bounds, { padding: [40, 40] });
                }
            });
        </script>
    @endpush
</x-app-layout>
