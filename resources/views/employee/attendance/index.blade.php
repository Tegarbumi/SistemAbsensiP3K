<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #attendance-map { height: 230px; width: 100%; border-radius: 0.75rem; z-index: 10; }
        @media (min-width: 640px) {
            #attendance-map { height: 260px; }
        }
        .custom-map-icon { background: none; border: none; }
    </style>
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <span>{{ __('Presensi Kehadiran PPPK') }}</span>
            </h2>
            <div class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium hidden xs:block">
                Sistem Validasi GPS & Swafoto
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            {{-- Info Card Identitas Pegawai & Jam Server --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-start gap-3.5 min-w-0">
                        @if(isset($employee) && $employee->foto)
                            <img src="{{ asset('storage/' . $employee->foto) }}" alt="{{ $employee->nama }}" class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shrink-0 shadow-2xs">
                        @else
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-blue-50 dark:bg-slate-700 border border-blue-100 dark:border-slate-600 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-base sm:text-lg shrink-0 shadow-2xs">
                                {{ strtoupper(substr($employee->nama ?? Auth::user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40">
                                    PPPK Aktif
                                </span>
                            </div>
                            <h3 class="text-base sm:text-xl font-bold text-slate-900 dark:text-white tracking-tight truncate">
                                {{ $employee->nama ?? 'Nama Tidak Ditemukan' }}
                            </h3>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                <span>NIP: <strong class="text-slate-700 dark:text-slate-200 font-mono">{{ $employee->nip ?? '-' }}</strong></span>
                                <span>&bull;</span>
                                <span class="truncate">{{ $employee->jabatan ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Display Tanggal & Jam Server -->
                    <div class="w-full md:w-auto p-3 sm:p-4 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/70 dark:border-slate-700/60 flex items-center justify-between md:justify-end gap-4 shrink-0">
                        <div>
                            <span class="text-[10px] sm:text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">
                                Tanggal Server
                            </span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 block">
                                {{ $serverDateFormatted }}
                            </span>
                        </div>
                        <div class="text-right border-l border-slate-200 dark:border-slate-700/80 pl-4">
                            <span class="text-[10px] sm:text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">
                                Jam Resmi (WIB)
                            </span>
                            <div class="text-2xl sm:text-3xl font-bold text-blue-700 dark:text-blue-400 font-mono tabular-nums leading-none mt-0.5">
                                <span id="clock">{{ $serverTime }}</span>
                                <span class="text-xs font-sans font-medium text-slate-500 dark:text-slate-400">WIB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Card Absensi Hari Ini --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200" id="status-card">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-700/80">
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        Status Presensi Hari Ini
                    </h4>
                    <span class="text-[11px] text-slate-400 dark:text-slate-500">Real-time sync</span>
                </div>
                
                @if(!$attendance)
                    <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 p-3.5 sm:p-4 rounded-xl flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-300 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm text-amber-900 dark:text-amber-200 font-semibold">Belum Melakukan Absen Masuk</p>
                            <p class="text-[11px] sm:text-xs text-amber-700 dark:text-amber-400 mt-0.5">Silakan ambil swafoto dan pastikan GPS Anda terhubung untuk check-in.</p>
                        </div>
                    </div>
                @elseif($attendance && !$attendance->jam_pulang)
                    <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 p-3.5 sm:p-4 rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-emerald-900 dark:text-emerald-200 font-bold">Sudah Check-In Masuk</p>
                                <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5 font-mono">Pukul {{ $attendance->jam_masuk }} WIB &bull; Status: {{ ucfirst($attendance->status) }}</p>
                            </div>
                        </div>
                        @if($attendance->selfie_masuk)
                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                <span class="text-[10px] text-emerald-700 dark:text-emerald-400 font-medium">Foto Masuk:</span>
                                <img src="{{ asset('storage/' . $attendance->selfie_masuk) }}" class="h-12 w-12 sm:h-14 sm:w-14 object-cover rounded-xl shadow-2xs border border-emerald-200 dark:border-emerald-700" alt="Selfie Masuk">
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800/60 p-3.5 sm:p-4 rounded-xl">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 flex items-center justify-center shrink-0">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm text-blue-950 dark:text-blue-200 font-bold">Presensi Hari Ini Selesai</p>
                                    <p class="text-[11px] sm:text-xs text-blue-700 dark:text-blue-300 mt-0.5 font-mono">
                                        Masuk: <strong class="font-bold">{{ $attendance->jam_masuk }}</strong> | Pulang: <strong class="font-bold">{{ $attendance->jam_pulang }}</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5 self-end sm:self-auto">
                                @if($attendance->selfie_masuk)
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $attendance->selfie_masuk) }}" class="h-12 w-12 object-cover rounded-lg shadow-2xs border border-blue-200 dark:border-blue-700" alt="Selfie Masuk">
                                        <span class="text-[9px] text-blue-700 dark:text-blue-400 block mt-0.5 font-medium">Masuk</span>
                                    </div>
                                @endif
                                @if($attendance->selfie_pulang)
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $attendance->selfie_pulang) }}" class="h-12 w-12 object-cover rounded-lg shadow-2xs border border-blue-200 dark:border-blue-700" alt="Selfie Pulang">
                                        <span class="text-[9px] text-blue-700 dark:text-blue-400 block mt-0.5 font-medium">Pulang</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($attendance)
                    <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-slate-700/80 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-2.5">
                        <p class="text-[11px] text-amber-800 dark:text-amber-300 bg-amber-50/70 dark:bg-amber-950/30 px-3 py-1.5 rounded-lg border border-amber-200/60 dark:border-amber-800/40">
                            💡 Salah unit kerja, lokasi, atau foto? Anda dapat melakukan <strong>Absen Ulang</strong>.
                        </p>
                        <button type="button" onclick="openResetModal()" class="inline-flex items-center justify-center px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold shadow-xs transition gap-1.5 whitespace-nowrap cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Absen Ulang / Koreksi
                        </button>
                    </div>
                @endif
            </div>

            {{-- Unit Selection Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200" id="unit-selection-card">
                <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 sm:gap-4">
                    <div class="flex-1 min-w-0">
                        <label for="select-unit" class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </span>
                            Pilih Unit Kerja Penugasan
                        </label>
                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1">
                            @if($attendance)
                                <span class="text-emerald-600 dark:text-emerald-400 font-medium">✓ Unit absensi telah tercatat untuk hari ini (terkunci).</span>
                            @else
                                Sesuaikan unit penugasan hari ini (Kantor Dinas, Perpustakaan Daerah, atau Penugasan Lapangan).
                            @endif
                        </p>
                    </div>
                    <div class="w-full md:w-80 shrink-0">
                        <select id="select-unit" {{ $attendance ? 'disabled' : '' }} onchange="onUnitChanged(this.value)" class="block w-full rounded-xl border-slate-300 dark:border-slate-700 shadow-2xs text-xs sm:text-sm font-semibold focus:border-blue-500 focus:ring-blue-500 {{ $attendance ? 'bg-slate-100 dark:bg-slate-900 cursor-not-allowed text-slate-500' : 'bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white hover:bg-slate-100/70 transition cursor-pointer' }}">
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ (string)($selectedUnitId) === (string)$unit->id ? 'selected' : '' }}>
                                    {{ $unit->is_dinas_lapangan ? '📍 ' : '🏢 ' }}{{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Dinas Lapangan Banner --}}
            <div id="dinas-lapangan-banner" class="{{ $isDinasLapangan ? '' : 'hidden' }} bg-emerald-50/90 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 p-4 rounded-xl shadow-2xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-600 text-white rounded-lg shadow-2xs shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-emerald-950 dark:text-emerald-200">Mode Presensi Dinas Lapangan (Sistem Sharelok GPS)</h4>
                        <p class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-0.5">Titik koordinat posisi Anda saat ini otomatis menjadi titik absensi (bebas batas radius kantor).</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200 border border-emerald-200 dark:border-emerald-700 whitespace-nowrap">
                    📍 Titik Bebas Lapangan
                </span>
            </div>

            {{-- Warning jika kantor belum diatur --}}
            <div id="office-missing-warning" class="{{ (!$officeLocation && !$isDinasLapangan) ? '' : 'hidden' }} bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/60 p-4 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-300 flex items-center justify-center shrink-0">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-rose-700 dark:text-rose-300 font-medium">Lokasi kantor belum diatur untuk unit ini. Silakan pilih unit lain atau hubungi admin.</p>
                    </div>
                </div>
            </div>

            {{-- Grid Kamera & GPS Lokasi (2 Kolom di Desktop, 1 Kolom di Mobile) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                
                {{-- CAMERA SECTION --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-700/80">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                Verifikasi Swafoto (Selfie)
                            </h4>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500">Kamera Depan</span>
                        </div>
                        
                        <!-- Viewfinder Container -->
                        <div class="relative w-full max-w-[280px] xs:max-w-xs sm:max-w-sm mx-auto aspect-[3/4] bg-slate-900 rounded-2xl overflow-hidden border-2 border-slate-300 dark:border-slate-700 flex items-center justify-center shadow-md">
                            <video id="camera-video" class="absolute inset-0 w-full h-full object-cover transform scale-x-[-1]" autoplay playsinline></video>
                            <img id="camera-preview" class="absolute inset-0 w-full h-full object-cover hidden" alt="Preview Foto">
                            
                            <!-- Loading state -->
                            <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/80 text-white p-4 hidden">
                                <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-xs font-medium text-slate-300">Menghubungkan kamera...</p>
                            </div>

                            <!-- Error state -->
                            <div id="camera-error" class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center bg-rose-950/90 text-white hidden">
                                <svg class="h-10 w-10 text-rose-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-xs font-semibold text-rose-200" id="camera-error-text">Gagal mengakses kamera</p>
                                <button onclick="startCamera()" class="mt-3 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">Coba Lagi</button>
                            </div>
                            
                            <canvas id="camera-canvas" class="hidden"></canvas>
                        </div>
                    </div>
                    
                    <!-- Tombol Pengambilan Foto -->
                    <div class="mt-5 w-full max-w-[280px] xs:max-w-xs sm:max-w-sm mx-auto flex flex-col gap-2">
                        <button id="btn-capture" onclick="capturePhoto()" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-xs transition font-semibold text-sm cursor-pointer">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Ambil Foto Selfie</span>
                        </button>
                        <button id="btn-retake" onclick="retakePhoto()" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-700 hover:bg-slate-800 text-white rounded-xl shadow-xs transition font-semibold text-sm cursor-pointer hidden">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Ambil Ulang Foto</span>
                        </button>
                    </div>
                </div>

                {{-- GPS & MAP SECTION --}}
                <div class="space-y-4 sm:space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200">
                        <div class="flex justify-between items-center pb-3 mb-4 border-b border-slate-100 dark:border-slate-700/80">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                Lokasi GPS & Peta Presensi
                            </h4>
                            <button onclick="getLocation()" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg border border-blue-200/60 dark:border-slate-700 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Perbarui</span>
                            </button>
                        </div>
                        
                        <!-- Loading GPS -->
                        <div id="gps-loading" class="flex flex-col items-center justify-center py-6 text-center">
                            <svg class="animate-spin h-7 w-7 text-blue-600 dark:text-blue-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Mendeteksi koordinat satelit GPS...</p>
                        </div>
                        
                        <!-- GPS Error -->
                        <div id="gps-error" class="hidden bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/60 p-3.5 rounded-xl mb-3">
                            <p class="text-xs text-rose-700 dark:text-rose-300 font-medium" id="gps-error-text">Gagal mendapatkan lokasi.</p>
                        </div>
                        
                        <!-- GPS Data Grid (4 Mini Tiles) -->
                        <div id="gps-data" class="hidden space-y-3">
                            <div class="grid grid-cols-2 gap-2 sm:gap-3 text-xs">
                                <div class="bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-semibold block">Latitude</span>
                                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm mt-0.5 block truncate" id="val-lat">-</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-semibold block">Longitude</span>
                                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm mt-0.5 block truncate" id="val-lng">-</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-semibold block">Akurasi GPS</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm mt-0.5 block"><span id="val-acc">-</span> meter</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-semibold block">Jarak ke Kantor</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm mt-0.5 block"><span id="val-dist">-</span> meter</span>
                                </div>
                            </div>
                            
                            <!-- Radius Status Bar -->
                            <div class="pt-2 border-t border-slate-100 dark:border-slate-700/80">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">Status Validasi Radius:</span>
                                    <span id="radius-status" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Menunggu Lokasi</span>
                                </div>
                                <div class="flex flex-wrap items-center justify-between mt-2 text-[11px] text-slate-500 dark:text-slate-400 gap-2">
                                    <a id="btn-sharelok-gmaps" href="#" target="_blank" class="hidden inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                        <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        Buka Titik di Google Maps
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

                            <!-- Input Keterangan Dinas Lapangan -->
                            <div id="lokasi-lapangan-wrapper" class="{{ $isDinasLapangan ? '' : 'hidden' }} pt-2 border-t border-slate-100 dark:border-slate-700/80">
                                <label for="lokasi_lapangan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Nama Tempat / Lokasi Penugasan Lapangan:
                                </label>
                                <input type="text" id="lokasi_lapangan" placeholder="Contoh: Kantor Camat Pagaden, Survey Perpustakaan Desa, dll..." class="block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs shadow-2xs focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Keterangan ini dicatat bersama titik koordinat sharelok GPS Anda.</p>
                            </div>

                            <!-- Leaflet Map Canvas -->
                            <div class="mt-3">
                                <div id="attendance-map" class="shadow-inner border border-slate-200 dark:border-slate-700"></div>
                                <div class="flex flex-wrap items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 mt-2 gap-2">
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

                    {{-- ACTION BUTTONS SECTION --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200">
                        <div class="pb-3 mb-4 border-b border-slate-100 dark:border-slate-700/80">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                Tombol Aksi Presensi
                            </h4>
                        </div>
                        
                        <div id="action-alert" class="hidden mb-3 p-3 rounded-xl text-xs sm:text-sm"></div>

                        <!-- 2 Large Touch Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Check In Button -->
                            <div>
                                <button id="btn-checkin" onclick="submitAttendance('check-in')" disabled class="relative w-full flex items-center justify-center py-3.5 sm:py-4 px-4 border border-transparent text-sm sm:text-base font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-40 disabled:cursor-not-allowed shadow-xs transition cursor-pointer">
                                    <span id="text-checkin" class="flex items-center gap-2">
                                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Absen Masuk</span>
                                    </span>
                                    <svg id="spinner-checkin" class="hidden animate-spin ml-2 h-5 w-5 text-white absolute right-4 top-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                                @if($attendance)
                                    <p class="text-[11px] text-center text-emerald-600 dark:text-emerald-400 font-semibold mt-1.5">
                                        ✓ Tercatat Masuk ({{ $attendance->jam_masuk }})
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Check Out Button -->
                            <div>
                                <button id="btn-checkout" onclick="submitAttendance('check-out')" disabled class="relative w-full flex items-center justify-center py-3.5 sm:py-4 px-4 border border-transparent text-sm sm:text-base font-bold rounded-xl text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 disabled:opacity-40 disabled:cursor-not-allowed shadow-xs transition cursor-pointer">
                                    <span id="text-checkout" class="flex items-center gap-2">
                                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Absen Pulang</span>
                                    </span>
                                    <svg id="spinner-checkout" class="hidden animate-spin ml-2 h-5 w-5 text-white absolute right-4 top-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                                @if($attendance && $attendance->jam_pulang)
                                    <p class="text-[11px] text-center text-blue-600 dark:text-blue-400 font-semibold mt-1.5">
                                        ✓ Tercatat Pulang ({{ $attendance->jam_pulang }})
                                    </p>
                                @elseif(!$attendance)
                                    <p class="text-[11px] text-center text-slate-400 dark:text-slate-500 mt-1.5">
                                        Harap lakukan Absen Masuk terlebih dahulu
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($attendance && $attendance->jam_pulang)
                            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800/60 rounded-xl text-center text-xs text-blue-700 dark:text-blue-300 flex flex-col sm:flex-row items-center justify-between gap-2">
                                <span>Presensi hari ini telah lengkap (Absen Masuk & Pulang telah tercatat).</span>
                                <button type="button" onclick="openResetModal()" class="font-semibold text-blue-800 dark:text-blue-200 underline hover:text-blue-950 dark:hover:text-white cursor-pointer whitespace-nowrap">
                                    Koreksi / Absen Ulang
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Modal Konfirmasi Absen Ulang --}}
            <div id="modal-reset-attendance" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 hidden backdrop-blur-xs p-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full p-5 sm:p-6 shadow-2xl border border-slate-200 dark:border-slate-700 transform transition-all">
                    <div class="flex items-center gap-3 text-amber-600 dark:text-amber-400 mb-3">
                        <div class="p-2.5 bg-amber-100 dark:bg-amber-950/50 rounded-xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Konfirmasi Absen Ulang</h3>
                    </div>
                    
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">
                        Apakah Anda yakin ingin melakukan absensi ulang? Data absensi hari ini akan di-reset agar Anda dapat memilih unit kerja yang benar dan mengambil presensi ulang.
                    </p>

                    <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-xl p-3 text-xs text-amber-800 dark:text-amber-300 mb-4 flex items-start gap-2">
                        <span class="text-base leading-none">⚠️</span>
                        <span>Tindakan ini akan tercatat dalam riwayat sistem (audit trail) untuk menjaga integritas absensi.</span>
                    </div>

                    <div class="mb-5">
                        <label for="alasan-reset" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Alasan Absen Ulang (opsional):
                        </label>
                        <input type="text" id="alasan-reset" placeholder="Contoh: Salah pilih unit / Foto selfie buram" class="block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs shadow-2xs focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-2.5">
                        <button type="button" onclick="closeResetModal()" class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl text-xs sm:text-sm font-semibold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="button" id="btn-confirm-reset" onclick="submitResetAttendance()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-xs transition gap-2 cursor-pointer">
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
