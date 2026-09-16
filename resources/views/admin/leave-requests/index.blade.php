<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Persetujuan Pengajuan Izin / Sakit') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Dinas Kearsipan dan Perpustakaan Kabupaten Subang</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                    <span class="text-xs text-gray-500 block">Total Pengajuan</span>
                    <span class="text-2xl font-black text-gray-900">{{ $summary['total'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-sm text-center bg-amber-50/40">
                    <span class="text-xs text-amber-700 font-semibold block">Menunggu Persetujuan</span>
                    <span class="text-2xl font-black text-amber-700">{{ $summary['menunggu'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-emerald-200 shadow-sm text-center bg-emerald-50/40">
                    <span class="text-xs text-emerald-700 font-semibold block">Disetujui</span>
                    <span class="text-2xl font-black text-emerald-700">{{ $summary['disetujui'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-red-200 shadow-sm text-center bg-red-50/40">
                    <span class="text-xs text-red-700 font-semibold block">Ditolak</span>
                    <span class="text-2xl font-black text-red-700">{{ $summary['ditolak'] }}</span>
                </div>
            </div>

            <!-- Filter & Pencarian -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                <form method="GET" action="{{ route('admin.leave-requests.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cari PPPK</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIP..." class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">Semua Status</option>
                            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label for="jenis" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Jenis Pengajuan</label>
                        <select name="jenis" id="jenis" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">Semua Jenis</option>
                            <option value="izin" {{ request('jenis') === 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('jenis') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="cuti" {{ request('jenis') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="dinas" {{ request('jenis') === 'dinas' ? 'selected' : '' }}>Dinas</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Cari
                        </button>
                        <a href="{{ route('admin.leave-requests.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Pengajuan Izin -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Pegawai PPPK</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Jenis</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Rentang Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Alasan</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Dokumen</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Status</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($leaveRequests as $req)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900">{{ $req->employee?->nama ?: '-' }}</div>
                                        <div class="text-xs text-gray-500 font-mono">NIP: {{ $req->employee?->nip ?: '-' }}</div>
                                        <div class="text-xs text-gray-400">{{ $req->employee?->unit?->nama ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeClasses = match($req->jenis) {
                                                'izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'sakit' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                'cuti' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                                'dinas' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                            {{ ucfirst($req->jenis) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                                        <div class="font-semibold">{{ \Carbon\Carbon::parse($req->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }}</div>
                                        @if($req->tanggal_mulai != $req->tanggal_selesai)
                                            <div class="text-gray-400">s/d {{ \Carbon\Carbon::parse($req->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-800 max-w-xs truncate">{{ $req->alasan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                        @if($req->dokumen)
                                            <a href="{{ asset('storage/' . $req->dokumen) }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-semibold">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($req->status === 'disetujui')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Disetujui
                                            </span>
                                        @elseif($req->status === 'ditolak')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if($req->status === 'menunggu')
                                                <!-- Tombol Setuju -->
                                                <form action="{{ route('admin.leave-requests.approve', $req) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pengajuan {{ $req->jenis }} ini? Data absensi akan otomatis dicatat.');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition font-semibold">
                                                        Setujui
                                                    </button>
                                                </form>

                                                <!-- Tombol Tolak -->
                                                <form action="{{ route('admin.leave-requests.reject', $req) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pengajuan {{ $req->jenis }} ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-semibold">
                                                        Tolak
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('admin.leave-requests.show', $req) }}" class="p-1.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition" title="Lihat Rincian">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada data pengajuan permohonan izin / sakit ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leaveRequests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
