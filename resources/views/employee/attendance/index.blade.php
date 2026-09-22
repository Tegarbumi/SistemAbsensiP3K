<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #attendance-map { height: 240px; width: 100%; border-radius: 0.5rem; z-index: 10; }
        .custom-map-icon { background: none; border: none; }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Info Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $employee->nama ?? 'Nama Tidak Ditemukan' }}</h3>
                            <p class="text-gray-600">{{ $employee->jabatan ?? '-' }} | {{ $employee->unit->nama ?? '-' }}</p>
                        </div>
                        <div class="mt-4 md:mt-0 text-left md:text-right">
                            <p class="text-lg font-semibold text-gray-700">{{ $serverDateFormatted }}</p>
                            <p class="text-3xl font-bold text-indigo-600 font-mono"><span id="clock">{{ $serverTime }}</span> <span class="text-sm font-sans font-medium text-gray-500">WIB</span></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" id="status-card">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Status Absensi Hari Ini</h4>
                    
                    @if(!$attendance)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 font-medium">Belum Check-in</p>
                                </div>
                            </div>
                        </div>
                    @elseif($attendance && !$attendance->jam_pulang)
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md flex flex-col sm:flex-row justify-between items-center">
                            <div class="flex items-center mb-4 sm:mb-0">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700 font-medium">Sudah Check-in ({{ $attendance->jam_masuk }})</p>
                                </div>
                            </div>
                            @if($attendance->selfie_masuk)
                                <img src="{{ asset('storage/' . $attendance->selfie_masuk) }}" class="h-20 w-20 object-cover rounded-lg shadow-sm border border-gray-200" alt="Selfie Masuk">
                            @endif
                        </div>
                    @else
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1 flex flex-col sm:flex-row gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm text-blue-700 font-medium mb-2">Sudah Check-in & Check-out</p>
                                        <div class="grid grid-cols-2 gap-4 text-sm text-blue-800">
                                            <div>
                                                <span class="block font-semibold">Masuk:</span>
                                                {{ $attendance->jam_masuk }}
                                            </div>
                                            <div>
                                                <span class="block font-semibold">Pulang:</span>
                                                {{ $attendance->jam_pulang }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        @if($attendance->selfie_masuk)
                                            <div class="text-center">
                                                <img src="{{ asset('storage/' . $attendance->selfie_masuk) }}" class="h-16 w-16 object-cover rounded shadow-sm border border-blue-200" alt="Selfie Masuk">
                                                <span class="text-xs text-blue-600 block mt-1">Masuk</span>
                                            </div>
                                        @endif
                                        @if($attendance->selfie_pulang)
                                            <div class="text-center">
                                                <img src="{{ asset('storage/' . $attendance->selfie_pulang) }}" class="h-16 w-16 object-cover rounded shadow-sm border border-blue-200" alt="Selfie Pulang">
                                                <span class="text-xs text-blue-600 block mt-1">Pulang</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($attendance)
                        <div class="mt-4 pt-3 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                            <p class="text-xs text-amber-800 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">
                                💡 Salah memilih unit kerja, lokasi, atau foto selfie? Anda dapat melakukan <strong>Absen Ulang</strong>.
                            </p>
                            <button type="button" onclick="openResetModal()" class="inline-flex items-center px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold shadow-xs transition gap-1.5 whitespace-nowrap cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Absen Ulang / Koreksi Absen
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Unit Selection Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" id="unit-selection-card">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <label for="select-unit" class="text-base font-bold text-gray-800 flex items-center gap-2">
                                <span class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </span>
                                Pilih Unit Kerja / Penugasan Absensi
                            </label>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($attendance)
                                    <span class="text-emerald-600 font-medium">✓ Unit absensi telah tercatat untuk hari ini (terkunci).</span>
                                @else
                                    Sesuaikan lokasi presensi dengan tugas kerja Anda hari ini (Kantor Dinas, Perpustakaan Daerah, atau Lapangan).
                                @endif
                            </p>
                        </div>
                        <div class="w-full md:w-96">
                            <select id="select-unit" {{ $attendance ? 'disabled' : '' }} onchange="onUnitChanged(this.value)" class="block w-full rounded-xl border-gray-300 shadow-xs text-sm font-semibold focus:border-indigo-500 focus:ring-indigo-500 {{ $attendance ? 'bg-gray-100 cursor-not-allowed text-gray-600' : 'bg-indigo-50 text-indigo-950 hover:bg-indigo-100/50 transition cursor-pointer' }}">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ (string)($selectedUnitId) === (string)$unit->id ? 'selected' : '' }}>
                                        {{ $unit->is_dinas_lapangan ? '📍 ' : '🏢 ' }}{{ $unit->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dinas Lapangan Banner --}}
            <div id="dinas-lapangan-banner" class="{{ $isDinasLapangan ? '' : 'hidden' }} bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-emerald-600 text-white rounded-xl shadow-xs flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-emerald-950">Mode Presensi Dinas Lapangan (Sistem Sharelok GPS)</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">Titik koordinat posisi Anda saat ini otomatis menjadi titik absensi (bebas batas radius kantor).</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 whitespace-nowrap">
                    📍 Titik Bebas Lapangan
                </span>
            </div>

            {{-- Warning jika kantor belum diatur --}}
            <div id="office-missing-warning" class="{{ (!$officeLocation && !$isDinasLapangan) ? '' : 'hidden' }} bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">Lokasi kantor belum diatur untuk unit ini. Silakan pilih unit lain atau hubungi admin.</p>
                    </div>
                </div>
            </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Camera Section --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Foto Selfie</h4>
                            
                            <div class="relative w-full max-w-sm mx-auto aspect-[3/4] bg-gray-200 rounded-xl overflow-hidden border-2 border-gray-300 flex items-center justify-center shadow-inner">
                                <video id="camera-video" class="absolute inset-0 w-full h-full object-cover transform scale-x-[-1]" autoplay playsinline></video>
                                <img id="camera-preview" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview Foto">
                                <div id="camera-loading" class="absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
                                    <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <div id="camera-error" class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center bg-red-50 hidden">
                                    <svg class="h-10 w-10 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <p class="text-sm text-red-600 font-medium" id="camera-error-text">Gagal mengakses kamera</p>
                                    <button onclick="startCamera()" class="mt-3 px-3 py-1 bg-red-100 text-red-700 rounded-md text-sm hover:bg-red-200 transition">Coba Lagi</button>
                                </div>
                                
                                <canvas id="camera-canvas" class="hidden"></canvas>
                            </div>
                            
                            <div class="mt-6 flex justify-center space-x-4">
                                <button id="btn-capture" onclick="capturePhoto()" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow transition font-medium">
                                    Ambil Foto
                                </button>
                                <button id="btn-retake" onclick="retakePhoto()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow transition font-medium hidden">
                                    Ambil Ulang
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- GPS Section & Actions --}}
                    <div class="space-y-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-semibold text-gray-800">Lokasi Anda</h4>
                                    <button onclick="getLocation()" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Perbarui
                                    </button>
                                </div>
                                
                                <div id="gps-loading" class="flex flex-col items-center justify-center py-8">
                                    <svg class="animate-spin h-8 w-8 text-indigo-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm">Mencari lokasi Anda...</p>
                                </div>
                                
                                <div id="gps-error" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-md mb-4">
                                    <p class="text-sm text-red-700 font-medium" id="gps-error-text">Gagal mendapatkan lokasi.</p>
                                </div>
                                
                                <div id="gps-data" class="hidden">
                                    <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-sm">
                                        <div>
                                            <p class="text-gray-500">Latitude</p>
                                            <p class="font-medium text-gray-800" id="val-lat">-</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Longitude</p>
                                            <p class="font-medium text-gray-800" id="val-lng">-</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Akurasi GPS</p>
                                            <p class="font-medium text-gray-800"><span id="val-acc">-</span> meter</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Jarak dari Kantor</p>
                                            <p class="font-medium text-gray-800"><span id="val-dist">-</span> meter</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Status Jarak / Lokasi:</span>
                                            <span id="radius-status" class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Menunggu Lokasi</span>
                                        </div>
                                        <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                                            <a id="btn-sharelok-gmaps" href="#" target="_blank" class="hidden inline-flex items-center text-indigo-600 hover:text-indigo-800 font-semibold">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Buka Sharelok di Google Maps
                                            </a>
                                            <span class="ml-auto" id="radius-mode-info">
                                                @if($isDinasLapangan)
                                                    Mode: Bebas Radius (Dinas Lapangan)
                                                @else
                                                    Maksimal radius: {{ $officeLocation ? $officeLocation->radius_meter : 100 }} meter
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div id="lokasi-lapangan-wrapper" class="{{ $isDinasLapangan ? '' : 'hidden' }} mt-4 pt-3 border-t border-gray-100">
                                        <label for="lokasi_lapangan" class="block text-xs font-semibold text-gray-700 mb-1">
                                            Nama Tempat / Keterangan Penugasan Lapangan:
                                        </label>
                                        <input type="text" id="lokasi_lapangan" placeholder="Contoh: Kantor Camat Pagaden, Survey Lapangan, dll..." class="block w-full rounded-lg border-gray-300 text-xs shadow-xs focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="text-[11px] text-gray-400 mt-1">Keterangan ini akan otomatis dicatat bersama titik sharelok GPS Anda.</p>
                                    </div>

                                    {{-- OpenStreetMap + Leaflet Map --}}
                                    <div class="mt-4">
                                        <div id="attendance-map" class="shadow-inner border border-gray-200"></div>
                                        <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                                            <span class="flex items-center {{ $isDinasLapangan ? 'hidden' : '' }}" id="legend-office">
                                                <span class="inline-block mr-1">🏢</span> <span id="legend-office-name">{{ $officeLocation ? $officeLocation->nama : 'Kantor' }} & Radius</span>
                                            </span>
                                            <span class="flex items-center" id="legend-user">
                                                <span class="inline-block mr-1" id="legend-user-icon">{{ $isDinasLapangan ? '📍' : '👤' }}</span> <span id="legend-user-text">{{ $isDinasLapangan ? 'Posisi Sharelok Anda (Dinas Lapangan)' : 'Posisi Anda (Pegawai)' }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h4>
                                
                                <div id="action-alert" class="hidden mb-4 p-4 rounded-md text-sm"></div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <button id="btn-checkin" onclick="submitAttendance('check-in')" disabled class="relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed shadow transition">
                                            <span id="text-checkin" class="text-lg">Absen Masuk</span>
                                            <svg id="spinner-checkin" class="hidden animate-spin ml-2 h-5 w-5 text-white absolute right-4 top-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                        @if($attendance)
                                            <p class="text-xs text-center text-emerald-600 font-medium mt-2">
                                                ✓ Sudah Absen Masuk ({{ $attendance->jam_masuk }})
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <button id="btn-checkout" onclick="submitAttendance('check-out')" disabled class="relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed shadow transition">
                                            <span id="text-checkout" class="text-lg">Absen Pulang</span>
                                            <svg id="spinner-checkout" class="hidden animate-spin ml-2 h-5 w-5 text-white absolute right-4 top-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                        @if($attendance && $attendance->jam_pulang)
                                            <p class="text-xs text-center text-blue-600 font-medium mt-2">
                                                ✓ Sudah Absen Pulang ({{ $attendance->jam_pulang }})
                                            </p>
                                        @elseif(!$attendance)
                                            <p class="text-xs text-center text-gray-500 mt-2">
                                                Lakukan Absen Masuk terlebih dahulu
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @if($attendance && $attendance->jam_pulang)
                                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-center text-sm text-blue-700 flex flex-col sm:flex-row items-center justify-between gap-2">
                                        <span>Presensi hari ini telah lengkap (Absen Masuk & Pulang telah tercatat).</span>
                                        <button type="button" onclick="openResetModal()" class="text-xs font-semibold text-blue-800 underline hover:text-blue-950 cursor-pointer whitespace-nowrap">
                                            Koreksi / Absen Ulang
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            {{-- Modal Konfirmasi Absen Ulang --}}
            <div id="modal-reset-attendance" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 hidden backdrop-blur-xs p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 transform transition-all">
                    <div class="flex items-center gap-3 text-amber-600 mb-4">
                        <div class="p-2.5 bg-amber-100 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Konfirmasi Absen Ulang</h3>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4">
                        Apakah Anda yakin ingin melakukan absensi ulang? Data absensi hari ini akan di-reset agar Anda dapat memilih unit kerja yang benar dan mengambil presensi ulang.
                    </p>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-800 mb-4 flex items-start gap-2">
                        <span class="text-base leading-none">⚠️</span>
                        <span>Tindakan ini akan tercatat dalam riwayat sistem (audit trail) untuk menjaga integritas absensi.</span>
                    </div>

                    <div class="mb-5">
                        <label for="alasan-reset" class="block text-xs font-semibold text-gray-700 mb-1">
                            Alasan Absen Ulang (opsional):
                        </label>
                        <input type="text" id="alasan-reset" placeholder="Contoh: Salah pilih unit / Foto selfie buram" class="block w-full rounded-lg border-gray-300 text-xs shadow-xs focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeResetModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition cursor-pointer">
                            Batal
                        </button>
                        <button type="button" id="btn-confirm-reset" onclick="submitResetAttendance()" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-semibold shadow-xs transition gap-2 cursor-pointer">
                            <span id="text-confirm-reset">Ya, Reset & Absen Ulang</span>
                            <svg id="spinner-confirm-reset" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Server variables
        const serverTimeStr = '{{ $serverTime }}';
        let [serverHours, serverMinutes, serverSeconds] = serverTimeStr.split(':').map(Number);
        
        const unitsData = @json($unitsWithOffice);
        let selectedUnitId = {{ $selectedUnitId ?? 'null' }};
        let currentUnit = unitsData.find(u => u.id == selectedUnitId) || (unitsData.length > 0 ? unitsData[0] : null);

        let officeLat = currentUnit ? currentUnit.office_lat : 0;
        let officeLng = currentUnit ? currentUnit.office_lng : 0;
        let maxRadius = currentUnit ? currentUnit.office_radius : 0;
        let officeName = currentUnit ? currentUnit.office_name : '';
        let isDinasLapangan = currentUnit ? currentUnit.is_dinas_lapangan : false;

        const routeStatus = '{{ route('attendance.status') }}';
        const routeCheckIn = '{{ route('attendance.checkin') }}';
        const routeCheckOut = '{{ route('attendance.checkout') }}';
        const routeReset = '{{ route('attendance.reset') }}';
        
        let csrfToken = '';
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (tokenMeta) {
            csrfToken = tokenMeta.content;
        }

        // State variables
        let currentLat = null;
        let currentLng = null;
        let currentAcc = null;
        let currentDist = null;
        let isWithinRadius = false;
        
        let videoStream = null;
        let capturedBlob = null;
        
        let hasCheckedIn = {{ $attendance ? 'true' : 'false' }};
        let hasCheckedOut = {{ ($attendance && $attendance->jam_pulang) ? 'true' : 'false' }};
        
        let isSubmitting = false;

        // DOM Elements
        const clockEl = document.getElementById('clock');
        
        const videoEl = document.getElementById('camera-video');
        const previewEl = document.getElementById('camera-preview');
        const canvasEl = document.getElementById('camera-canvas');
        const btnCapture = document.getElementById('btn-capture');
        const btnRetake = document.getElementById('btn-retake');
        const camLoading = document.getElementById('camera-loading');
        const camError = document.getElementById('camera-error');
        const camErrorText = document.getElementById('camera-error-text');
        
        const gpsLoading = document.getElementById('gps-loading');
        const gpsError = document.getElementById('gps-error');
        const gpsErrorText = document.getElementById('gps-error-text');
        const gpsData = document.getElementById('gps-data');
        const valLat = document.getElementById('val-lat');
        const valLng = document.getElementById('val-lng');
        const valAcc = document.getElementById('val-acc');
        const valDist = document.getElementById('val-dist');
        const radiusStatus = document.getElementById('radius-status');
        
        const btnCheckin = document.getElementById('btn-checkin');
        const btnCheckout = document.getElementById('btn-checkout');
        const spinnerCheckin = document.getElementById('spinner-checkin');
        const spinnerCheckout = document.getElementById('spinner-checkout');
        const textCheckin = document.getElementById('text-checkin');
        const textCheckout = document.getElementById('text-checkout');
        const actionAlert = document.getElementById('action-alert');

        // Clock implementation
        function updateClock() {
            serverSeconds++;
            if (serverSeconds >= 60) {
                serverSeconds = 0;
                serverMinutes++;
                if (serverMinutes >= 60) {
                    serverMinutes = 0;
                    serverHours++;
                    if (serverHours >= 24) serverHours = 0;
                }
            }
            
            const h = String(serverHours).padStart(2, '0');
            const m = String(serverMinutes).padStart(2, '0');
            const s = String(serverSeconds).padStart(2, '0');
            
            if (clockEl) clockEl.textContent = `${h}:${m}:${s}`;
        }
        setInterval(updateClock, 1000);

        // Status polling
        async function pollStatus() {
            try {
                const response = await fetch(routeStatus, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                
                if (data.server_time) {
                    const [h, m, s] = data.server_time.split(':').map(Number);
                    serverHours = h;
                    serverMinutes = m;
                    serverSeconds = s;
                }
                
                if (data.has_checked_in !== undefined) {
                    hasCheckedIn = data.has_checked_in;
                    hasCheckedOut = data.has_checked_out;
                    updateButtonState();
                }
            } catch (err) {
                console.error('Failed to poll status', err);
            }
        }
        setInterval(pollStatus, 60000);

        // Camera implementation
        async function startCamera() {
            if (!videoEl) return;
            
            camError.classList.add('hidden');
            camLoading.classList.remove('hidden');
            previewEl.classList.add('hidden');
            videoEl.classList.remove('hidden');
            btnCapture.classList.remove('hidden');
            btnRetake.classList.add('hidden');
            capturedBlob = null;
            
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                    audio: false
                });
                videoEl.srcObject = videoStream;
                camLoading.classList.add('hidden');
            } catch (err) {
                camLoading.classList.add('hidden');
                camError.classList.remove('hidden');
                camErrorText.textContent = 'Kamera tidak dapat diakses. Pastikan izin kamera telah diberikan.';
                console.error(err);
            }
            updateButtonState();
        }

        function stopCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
        }

        function capturePhoto() {
            if (!videoStream || !videoEl) return;
            
            canvasEl.width = videoEl.videoWidth;
            canvasEl.height = videoEl.videoHeight;
            const ctx = canvasEl.getContext('2d');
            
            // Mirror image horizontally to match preview
            ctx.translate(canvasEl.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(videoEl, 0, 0, canvasEl.width, canvasEl.height);
            ctx.setTransform(1, 0, 0, 1, 0, 0); // reset
            
            canvasEl.toBlob(blob => {
                capturedBlob = blob;
                const url = URL.createObjectURL(blob);
                previewEl.src = url;
                
                videoEl.classList.add('hidden');
                previewEl.classList.remove('hidden');
                btnCapture.classList.add('hidden');
                btnRetake.classList.remove('hidden');
                
                updateButtonState();
            }, 'image/jpeg', 0.8);
        }

        function retakePhoto() {
            startCamera();
        }

        // Dynamic Unit Selection handler
        function onUnitChanged(newUnitId) {
            selectedUnitId = parseInt(newUnitId);
            currentUnit = unitsData.find(u => u.id == selectedUnitId);
            if (!currentUnit) return;

            officeLat = currentUnit.office_lat;
            officeLng = currentUnit.office_lng;
            maxRadius = currentUnit.office_radius;
            officeName = currentUnit.office_name;
            isDinasLapangan = currentUnit.is_dinas_lapangan;

            // Update UI elements
            const dinasBanner = document.getElementById('dinas-lapangan-banner');
            const officeWarning = document.getElementById('office-missing-warning');
            const lokasiWrapper = document.getElementById('lokasi-lapangan-wrapper');
            const radiusModeInfo = document.getElementById('radius-mode-info');
            const legendOffice = document.getElementById('legend-office');
            const legendOfficeName = document.getElementById('legend-office-name');
            const legendUserIcon = document.getElementById('legend-user-icon');
            const legendUserText = document.getElementById('legend-user-text');

            if (isDinasLapangan) {
                if (dinasBanner) dinasBanner.classList.remove('hidden');
                if (officeWarning) officeWarning.classList.add('hidden');
                if (lokasiWrapper) lokasiWrapper.classList.remove('hidden');
                if (radiusModeInfo) radiusModeInfo.textContent = 'Mode: Bebas Radius (Dinas Lapangan)';
                if (legendOffice) legendOffice.classList.add('hidden');
                if (legendUserIcon) legendUserIcon.textContent = '📍';
                if (legendUserText) legendUserText.textContent = 'Posisi Sharelok Anda (Dinas Lapangan)';
            } else {
                if (dinasBanner) dinasBanner.classList.add('hidden');
                if (lokasiWrapper) lokasiWrapper.classList.add('hidden');
                if (officeLat === 0) {
                    if (officeWarning) officeWarning.classList.remove('hidden');
                    if (radiusModeInfo) radiusModeInfo.textContent = 'Lokasi kantor belum diatur';
                } else {
                    if (officeWarning) officeWarning.classList.add('hidden');
                    if (radiusModeInfo) radiusModeInfo.textContent = 'Maksimal radius: ' + maxRadius + ' meter';
                }
                if (legendOffice) legendOffice.classList.remove('hidden');
                if (legendOfficeName) legendOfficeName.textContent = (officeName || 'Kantor') + ' & Radius';
                if (legendUserIcon) legendUserIcon.textContent = '👤';
                if (legendUserText) legendUserText.textContent = 'Posisi Anda (Pegawai)';
            }

            // Recalculate distance and update map if GPS coordinates already exist
            if (currentLat !== null && currentLng !== null) {
                updateDistanceAndRadius();
                initOrUpdateMap(currentLat, currentLng, currentAcc, isWithinRadius);
            }
            updateButtonState();
        }

        function updateDistanceAndRadius() {
            if (currentLat === null || currentLng === null) return;

            if (isDinasLapangan) {
                currentDist = 0;
                isWithinRadius = true;
                valDist.textContent = 'Bebas (Sharelok)';
                radiusStatus.textContent = '📍 Lokasi Valid (Sharelok)';
                radiusStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700';
            } else if (officeLat !== 0) {
                currentDist = calculateDistance(currentLat, currentLng, officeLat, officeLng);
                isWithinRadius = currentDist <= maxRadius;
                valDist.textContent = Math.round(currentDist);

                if (isWithinRadius) {
                    radiusStatus.textContent = 'Dalam Radius (' + Math.round(currentDist) + 'm)';
                    radiusStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700';
                } else {
                    radiusStatus.textContent = 'Luar Radius (' + Math.round(currentDist) + 'm - Dinas Luar)';
                    radiusStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700';
                }
            } else {
                currentDist = null;
                isWithinRadius = false;
                valDist.textContent = '-';
                radiusStatus.textContent = 'Kantor Belum Diatur';
                radiusStatus.className = 'px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700';
            }
        }

        // GPS implementation
        function getLocation() {
            if (!gpsLoading) return;
            
            gpsLoading.classList.remove('hidden');
            gpsError.classList.add('hidden');
            gpsData.classList.add('hidden');
            
            if (!navigator.geolocation) {
                showGpsError('Geolocation tidak didukung oleh browser ini.');
                return;
            }
            
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    currentLat = pos.coords.latitude;
                    currentLng = pos.coords.longitude;
                    currentAcc = pos.coords.accuracy;
                    
                    valLat.textContent = currentLat.toFixed(6);
                    valLng.textContent = currentLng.toFixed(6);
                    valAcc.textContent = Math.round(currentAcc);
                    
                    const btnSharelokGmaps = document.getElementById('btn-sharelok-gmaps');
                    if (btnSharelokGmaps) {
                        btnSharelokGmaps.href = `https://www.google.com/maps?q=${currentLat},${currentLng}`;
                        btnSharelokGmaps.classList.remove('hidden');
                    }

                    updateDistanceAndRadius();
                    
                    gpsLoading.classList.add('hidden');
                    gpsData.classList.remove('hidden');

                    initOrUpdateMap(currentLat, currentLng, currentAcc, isWithinRadius);
                    
                    updateButtonState();
                },
                (err) => {
                    let msg = 'Gagal mendapatkan lokasi.';
                    if (err.code === 1) msg = 'Izin akses lokasi ditolak.';
                    if (err.code === 2) msg = 'Sinyal GPS tidak tersedia.';
                    if (err.code === 3) msg = 'Waktu pencarian lokasi habis.';
                    showGpsError(msg);
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        function showGpsError(msg) {
            gpsLoading.classList.add('hidden');
            gpsError.classList.remove('hidden');
            gpsErrorText.textContent = msg;
            currentLat = null;
            updateButtonState();
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371000; // Earth radius in meters
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        // Custom Leaflet Icons to visually differentiate Office vs Employee
        const officeIcon = L.divIcon({
            className: 'custom-map-icon',
            html: `
                <div style="position: relative; width: 36px; height: 42px;">
                    <div style="background-color: #2563eb; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(37,99,235,0.4); border: 2.5px solid #ffffff;">
                        <span style="transform: rotate(45deg); font-size: 16px; line-height: 1;">🏢</span>
                    </div>
                </div>
            `,
            iconSize: [36, 42],
            iconAnchor: [17, 38],
            popupAnchor: [1, -38]
        });

        const userOfficeIcon = L.divIcon({
            className: 'custom-map-icon',
            html: `
                <div style="position: relative; width: 36px; height: 42px;">
                    <div style="background-color: #6366f1; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(99,102,241,0.4); border: 2.5px solid #ffffff;">
                        <span style="transform: rotate(45deg); font-size: 16px; line-height: 1;">👤</span>
                    </div>
                </div>
            `,
            iconSize: [36, 42],
            iconAnchor: [17, 38],
            popupAnchor: [1, -38]
        });

        const userLapanganIcon = L.divIcon({
            className: 'custom-map-icon',
            html: `
                <div style="position: relative; width: 36px; height: 42px;">
                    <div style="background-color: #059669; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(5,150,105,0.4); border: 2.5px solid #ffffff;">
                        <span style="transform: rotate(45deg); font-size: 16px; line-height: 1;">📍</span>
                    </div>
                </div>
            `,
            iconSize: [36, 42],
            iconAnchor: [17, 38],
            popupAnchor: [1, -38]
        });

        let mapInstance = null;
        let officeMarker = null;
        let officeCircle = null;
        let userMarker = null;
        let userAccuracyCircle = null;

        function initOrUpdateMap(userLat, userLng, userAcc, isInside) {
            if (typeof L === 'undefined') return;
            const mapEl = document.getElementById('attendance-map');
            if (!mapEl) return;

            const hasOffice = (!isDinasLapangan && officeLat !== 0);
            const initialLat = hasOffice ? officeLat : userLat;
            const initialLng = hasOffice ? officeLng : userLng;

            if (!mapInstance) {
                mapInstance = L.map('attendance-map').setView([initialLat, initialLng], 16);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(mapInstance);
            }

            // Office Marker & Radius Circle Handling
            if (hasOffice) {
                if (!officeMarker) {
                    officeMarker = L.marker([officeLat, officeLng], { icon: officeIcon }).addTo(mapInstance);
                } else {
                    officeMarker.setLatLng([officeLat, officeLng]);
                    if (!mapInstance.hasLayer(officeMarker)) {
                        officeMarker.addTo(mapInstance);
                    }
                }
                officeMarker.bindPopup('<b>🏢 ' + (officeName || 'Kantor') + '</b><br>Radius absensi: ' + maxRadius + ' meter');

                if (!officeCircle) {
                    officeCircle = L.circle([officeLat, officeLng], {
                        color: isInside ? '#16a34a' : '#dc2626',
                        fillColor: isInside ? '#22c55e' : '#ef4444',
                        fillOpacity: 0.15,
                        radius: maxRadius
                    }).addTo(mapInstance);
                } else {
                    officeCircle.setLatLng([officeLat, officeLng]);
                    officeCircle.setRadius(maxRadius);
                    officeCircle.setStyle({
                        color: isInside ? '#16a34a' : '#dc2626',
                        fillColor: isInside ? '#22c55e' : '#ef4444'
                    });
                    if (!mapInstance.hasLayer(officeCircle)) {
                        officeCircle.addTo(mapInstance);
                    }
                }
            } else {
                // If Dinas Lapangan or office not configured, remove office layers
                if (officeMarker && mapInstance.hasLayer(officeMarker)) {
                    mapInstance.removeLayer(officeMarker);
                    officeMarker = null;
                }
                if (officeCircle && mapInstance.hasLayer(officeCircle)) {
                    mapInstance.removeLayer(officeCircle);
                    officeCircle = null;
                }
            }

            // User Marker Handling
            const currentUserIcon = isDinasLapangan ? userLapanganIcon : userOfficeIcon;
            if (!userMarker) {
                userMarker = L.marker([userLat, userLng], { icon: currentUserIcon }).addTo(mapInstance);
            } else {
                userMarker.setIcon(currentUserIcon);
                userMarker.setLatLng([userLat, userLng]);
                if (!mapInstance.hasLayer(userMarker)) {
                    userMarker.addTo(mapInstance);
                }
            }

            const userPopupContent = isDinasLapangan 
                ? '<b>📍 Titik Sharelok Anda (Dinas Lapangan)</b><br>Koordinat: ' + userLat.toFixed(6) + ', ' + userLng.toFixed(6)
                : '<b>👤 Posisi Anda (Pegawai)</b><br>Koordinat: ' + userLat.toFixed(6) + ', ' + userLng.toFixed(6);
            userMarker.bindPopup(userPopupContent);

            // User Accuracy Circle Handling
            const accColor = isDinasLapangan ? '#059669' : '#6366f1';
            const accFill = isDinasLapangan ? '#34d399' : '#818cf8';
            if (!userAccuracyCircle) {
                userAccuracyCircle = L.circle([userLat, userLng], {
                    color: accColor,
                    fillColor: accFill,
                    fillOpacity: 0.15,
                    radius: userAcc
                }).addTo(mapInstance);
            } else {
                userAccuracyCircle.setLatLng([userLat, userLng]);
                userAccuracyCircle.setRadius(userAcc);
                userAccuracyCircle.setStyle({ color: accColor, fillColor: accFill });
                if (!mapInstance.hasLayer(userAccuracyCircle)) {
                    userAccuracyCircle.addTo(mapInstance);
                }
            }

            // Map view bounds: If office exists, fit both; if dinas lapangan, focus directly on user standing position
            if (hasOffice) {
                const bounds = L.latLngBounds([
                    [officeLat, officeLng],
                    [userLat, userLng]
                ]);
                mapInstance.fitBounds(bounds.pad(0.25));
            } else {
                mapInstance.setView([userLat, userLng], 17);
            }

            setTimeout(() => {
                mapInstance.invalidateSize();
            }, 250);
        }

        // Logic & Submission
        function updateButtonState() {
            if (!btnCheckin || !btnCheckout) return;
            
            const isOfficeMissing = !isDinasLapangan && officeLat === 0;
            const isReady = currentLat !== null && capturedBlob !== null && !isSubmitting && !isOfficeMissing;
            
            // Check In button logic
            if (isReady && !hasCheckedIn) {
                btnCheckin.disabled = false;
            } else {
                btnCheckin.disabled = true;
            }
            
            // Check Out button logic
            if (isReady && hasCheckedIn && !hasCheckedOut) {
                btnCheckout.disabled = false;
            } else {
                btnCheckout.disabled = true;
            }
        }
        
        function showAlert(msg, isSuccess) {
            actionAlert.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
            if (isSuccess) {
                actionAlert.classList.add('bg-green-100', 'text-green-800');
            } else {
                actionAlert.classList.add('bg-red-100', 'text-red-800');
            }
            actionAlert.textContent = msg;
            
            setTimeout(() => {
                actionAlert.classList.add('hidden');
            }, 5000);
        }

        async function submitAttendance(type) {
            if (isSubmitting) return;
            
            isSubmitting = true;
            updateButtonState();
            
            const isCheckIn = type === 'check-in';
            const url = isCheckIn ? routeCheckIn : routeCheckOut;
            const btn = isCheckIn ? btnCheckin : btnCheckout;
            const spinner = isCheckIn ? spinnerCheckin : spinnerCheckout;
            const text = isCheckIn ? textCheckin : textCheckout;
            
            // Show loading state
            spinner.classList.remove('hidden');
            text.classList.add('opacity-0');
            
            const formData = new FormData();
            formData.append('latitude', currentLat);
            formData.append('longitude', currentLng);
            formData.append('accuracy', currentAcc);
            formData.append('selfie', capturedBlob, 'selfie.jpg');

            const selectUnit = document.getElementById('select-unit');
            if (selectUnit && selectUnit.value) {
                formData.append('unit_id', selectUnit.value);
            }

            const lokasiLapanganInput = document.getElementById('lokasi_lapangan');
            if (lokasiLapanganInput && lokasiLapanganInput.value) {
                formData.append('lokasi_lapangan', lokasiLapanganInput.value);
            }
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showAlert(data.message || 'Berhasil menyimpan absensi.', true);
                    
                    if (isCheckIn) {
                        hasCheckedIn = true;
                    } else {
                        hasCheckedOut = true;
                    }
                    
                    stopCamera();
                    capturedBlob = null;
                    currentLat = null;
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                    
                } else {
                    showAlert(data.message || 'Terjadi kesalahan. Silakan coba lagi.', false);
                    isSubmitting = false;
                }
            } catch (err) {
                console.error(err);
                showAlert('Gagal menghubungi server. Periksa koneksi internet Anda.', false);
                isSubmitting = false;
            } finally {
                spinner.classList.add('hidden');
                text.classList.remove('opacity-0');
                updateButtonState();
            }
        }

        function openResetModal() {
            const modal = document.getElementById('modal-reset-attendance');
            if (modal) modal.classList.remove('hidden');
        }

        function closeResetModal() {
            const modal = document.getElementById('modal-reset-attendance');
            if (modal) modal.classList.add('hidden');
        }

        async function submitResetAttendance() {
            const btn = document.getElementById('btn-confirm-reset');
            const spinner = document.getElementById('spinner-confirm-reset');
            const text = document.getElementById('text-confirm-reset');
            const alasanInput = document.getElementById('alasan-reset');

            if (spinner) spinner.classList.remove('hidden');
            if (text) text.classList.add('opacity-0');
            if (btn) btn.disabled = true;

            try {
                const response = await fetch(routeReset, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        alasan: alasanInput ? alasanInput.value : ''
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    closeResetModal();
                    showAlert(data.message || 'Absensi berhasil di-reset.', true);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    alert(data.message || 'Gagal mereset absensi.');
                    if (spinner) spinner.classList.add('hidden');
                    if (text) text.classList.remove('opacity-0');
                    if (btn) btn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat menghubungi server.');
                if (spinner) spinner.classList.add('hidden');
                if (text) text.classList.remove('opacity-0');
                if (btn) btn.disabled = false;
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('camera-video')) {
                startCamera();
                getLocation();
            }
        });
    </script>
    @endpush
</x-app-layout>
