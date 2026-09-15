<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Absensi PPPK - Dinas Kearsipan dan Perpustakaan Kabupaten Subang</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col justify-between">
    <!-- Header Navigasi -->
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-700 flex items-center justify-center text-white font-bold text-lg shadow">
                    DKP
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-900 leading-tight">Sistem Absensi PPPK</h1>
                    <p class="text-xs text-gray-500">Dinas Kearsipan dan Perpustakaan Kab. Subang</p>
                </div>
            </div>

            <nav class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->role === 'pppk')
                            <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                Buka Absensi
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                Dashboard Admin
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Masuk / Log in
                        </a>
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl w-full text-center space-y-8">
            <div class="space-y-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Pemerintah Kabupaten Subang
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Presensi Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Dinas Kearsipan dan Perpustakaan Kabupaten Subang. Layanan presensi online terverifikasi menggunakan kamera selfie dan titik lokasi GPS akurat.
                </p>
            </div>

            <!-- Tombol Utama -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                @auth
                    @if(Auth::user()->role === 'pppk')
                        <a href="{{ route('attendance.index') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-bold rounded-xl shadow-lg hover:shadow-xl transition">
                            Lakukan Absensi Sekarang
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-bold rounded-xl shadow-lg hover:shadow-xl transition">
                            Buka Dashboard Admin
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-bold rounded-xl shadow-lg hover:shadow-xl transition">
                        Masuk ke Portal Absensi
                    </a>
                @endauth
            </div>

            <!-- Kartu Petunjuk / Fitur -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left pt-6">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="w-8 h-8 rounded-md bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm mb-3">
                        1
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Kamera Selfie</h3>
                    <p class="text-xs text-gray-500">
                        Pengambilan foto selfie langsung dari kamera depan perangkat untuk verifikasi identitas resmi.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="w-8 h-8 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm mb-3">
                        2
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Validasi Radius GPS</h3>
                    <p class="text-xs text-gray-500">
                        Pemeriksaan jarak koordinat lokasi terhadap kantor dinas secara real-time pada peta interaktif.
                    </p>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="w-8 h-8 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm mb-3">
                        3
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Waktu Server Resmi</h3>
                    <p class="text-xs text-gray-500">
                        Pencatatan jam masuk dan jam pulang menggunakan jam resmi server, terlindungi dari manipulasi waktu lokal.
                    </p>
                </div>
            </div>

            <!-- Informasi Akun Uji Coba (Development Info) -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800 text-left max-w-xl mx-auto">
                <p class="font-semibold mb-2">Akun Percobaan yang Tersedia:</p>
                <div class="space-y-1 text-xs font-mono">
                    <p>• Akun PPPK: <span class="font-bold">pppk@subang.go.id</span> (Password: <span class="font-bold">password</span>)</p>
                    <p>• Akun Admin: <span class="font-bold">admin@subang.go.id</span> (Password: <span class="font-bold">password</span>)</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Dinas Kearsipan dan Perpustakaan Kabupaten Subang. Seluruh hak cipta dilindungi.
        </div>
    </footer>
</body>
</html>
