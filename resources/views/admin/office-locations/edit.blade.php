<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #pickerMap { height: 420px; border-radius: 0.75rem; z-index: 10; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Lokasi Kantor Absensi') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">{{ $location->nama }}</p>
            </div>
            <a href="{{ route('admin.office-locations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 sm:p-8">
                <form action="{{ route('admin.office-locations.update', $location) }}" method="POST" id="officeLocationForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Kolom Form Data -->
                        <div class="lg:col-span-5 space-y-5">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-200">
                                Informasi Kantor & Batasan
                            </h3>

                            <!-- Nama Lokasi -->
                            <div>
                                <label for="nama" class="block text-sm font-semibold text-gray-700">
                                    Nama Lokasi Kantor <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama', $location->nama) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nama') border-red-500 @enderror">
                                @error('nama')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit Terkait -->
                            <div>
                                <label for="unit_id" class="block text-sm font-semibold text-gray-700">
                                    Unit / Bidang Kerja Terkait
                                </label>
                                <select name="unit_id" id="unit_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('unit_id') border-red-500 @enderror">
                                    <option value="">Semua Unit (Kantor Pusat / Umum)</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id', $location->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Pilih jika lokasi ini khusus untuk unit tertentu saja.</p>
                                @error('unit_id')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat Lengkap -->
                            <div>
                                <label for="alamat" class="block text-sm font-semibold text-gray-700">
                                    Alamat Lengkap
                                </label>
                                <textarea name="alamat" id="alamat" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('alamat') border-red-500 @enderror">{{ old('alamat', $location->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Koordinat Latitude & Longitude -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-semibold text-gray-700">
                                        Latitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" step="0.0000001" name="latitude" id="latitude" value="{{ old('latitude', $location->latitude) }}" required class="mt-1 block w-full rounded-lg border-gray-300 font-mono shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('latitude') border-red-500 @enderror">
                                    @error('latitude')
                                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-semibold text-gray-700">
                                        Longitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" step="0.0000001" name="longitude" id="longitude" value="{{ old('longitude', $location->longitude) }}" required class="mt-1 block w-full rounded-lg border-gray-300 font-mono shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('longitude') border-red-500 @enderror">
                                    @error('longitude')
                                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Radius Meter -->
                            <div>
                                <div class="flex justify-between items-center">
                                    <label for="radius_meter" class="block text-sm font-semibold text-gray-700">
                                        Radius Absensi (meter) <span class="text-red-500">*</span>
                                    </label>
                                    <span id="radiusDisplay" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                        {{ old('radius_meter', $location->radius_meter) }} meter
                                    </span>
                                </div>
                                <input type="number" min="10" max="5000" name="radius_meter" id="radius_meter" value="{{ old('radius_meter', $location->radius_meter) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('radius_meter') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Jarak radius batas toleransi PPPK dapat melakukan check-in / check-out.</p>
                                @error('radius_meter')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700">
                                    Status Operasional <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                                    <option value="1" {{ old('status', $location->status ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status', $location->status ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Peta Interaktif Leaflet -->
                        <div class="lg:col-span-7 space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">
                                    Peta Koordinat & Radius Kantor
                                </h3>
                                <button type="button" id="btnCurrentLocation" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Gunakan Lokasi Saya Saat Ini
                                </button>
                            </div>

                            <p class="text-xs text-gray-500">
                                Geser penanda merah atau klik di peta untuk memperbarui koordinat.
                            </p>

                            <div id="pickerMap" class="w-full border border-gray-300 shadow-inner"></div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                        <a href="{{ route('admin.office-locations.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Perbarui Lokasi Kantor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const radiusInput = document.getElementById('radius_meter');
                const radiusDisplay = document.getElementById('radiusDisplay');

                let currentLat = parseFloat(latInput.value) || -6.5683;
                let currentLng = parseFloat(lngInput.value) || 107.7634;
                let currentRadius = parseInt(radiusInput.value) || 100;

                const map = L.map('pickerMap').setView([currentLat, currentLng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const marker = L.marker([currentLat, currentLng], { draggable: true }).addTo(map);
                const circle = L.circle([currentLat, currentLng], {
                    color: '#2563eb',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.2,
                    radius: currentRadius
                }).addTo(map);

                function updateCoordinates(lat, lng) {
                    latInput.value = lat.toFixed(7);
                    lngInput.value = lng.toFixed(7);
                    marker.setLatLng([lat, lng]);
                    circle.setLatLng([lat, lng]);
                }

                marker.on('dragend', function (e) {
                    const pos = e.target.getLatLng();
                    updateCoordinates(pos.lat, pos.lng);
                });

                map.on('click', function (e) {
                    updateCoordinates(e.latlng.lat, e.latlng.lng);
                });

                latInput.addEventListener('input', function () {
                    const lat = parseFloat(this.value);
                    const lng = parseFloat(lngInput.value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        marker.setLatLng([lat, lng]);
                        circle.setLatLng([lat, lng]);
                        map.panTo([lat, lng]);
                    }
                });

                lngInput.addEventListener('input', function () {
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(this.value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        marker.setLatLng([lat, lng]);
                        circle.setLatLng([lat, lng]);
                        map.panTo([lat, lng]);
                    }
                });

                radiusInput.addEventListener('input', function () {
                    const r = parseInt(this.value) || 10;
                    circle.setRadius(r);
                    if (radiusDisplay) {
                        radiusDisplay.textContent = r + ' meter';
                    }
                });

                document.getElementById('btnCurrentLocation').addEventListener('click', function () {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (pos) {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            updateCoordinates(lat, lng);
                            map.setView([lat, lng], 17);
                        }, function (err) {
                            alert('Gagal mengambil lokasi GPS: ' + err.message);
                        }, { enableHighAccuracy: true });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
