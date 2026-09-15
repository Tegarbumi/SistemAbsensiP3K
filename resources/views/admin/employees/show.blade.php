<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pegawai PPPK') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Data
                </a>
                <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profil Pegawai Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 md:p-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pb-6 border-b border-gray-200">
                    @if($employee->foto)
                        <img src="{{ asset('storage/' . $employee->foto) }}" class="w-24 h-24 rounded-2xl object-cover border-2 border-indigo-100 shadow-md" alt="{{ $employee->nama }}">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-extrabold text-3xl shadow-inner">
                            {{ strtoupper(substr($employee->nama, 0, 2)) }}
                        </div>
                    @endif

                    <div class="space-y-1">
                        <div class="flex items-center gap-3">
                            <h3 class="text-2xl font-extrabold text-gray-900">{{ $employee->nama }}</h3>
                            @if($employee->status)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 font-medium">
                            NIP: <span class="font-mono">{{ $employee->nip }}</span>
                            @if($employee->nomor_pppk)
                                &bull; No PPPK: <span class="font-mono">{{ $employee->nomor_pppk }}</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">
                            Jabatan: <span class="font-semibold text-gray-700">{{ $employee->jabatan ?: '-' }}</span> &bull;
                            Unit: <span class="font-semibold text-gray-700">{{ $employee->unit->nama ?? '-' }}</span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 text-sm">
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-medium">Alamat Email Login</span>
                        <span class="font-semibold text-gray-900 mt-0.5 block">{{ $employee->user->email ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-medium">Unit Kerja</span>
                        <span class="font-semibold text-gray-900 mt-0.5 block">{{ $employee->unit->nama ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-medium">Terdaftar Sejak</span>
                        <span class="font-semibold text-gray-900 mt-0.5 block">{{ $employee->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Presensi Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h4 class="text-base font-bold text-gray-900">Riwayat Presensi Terbaru (10 Terakhir)</h4>
                    <span class="text-xs text-gray-500 font-medium">Tercatat di sistem</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Tanggal</th>
                                <th class="px-6 py-3.5">Jam Masuk</th>
                                <th class="px-6 py-3.5">Jam Pulang</th>
                                <th class="px-6 py-3.5">Jarak Kantor</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-center">Foto Masuk</th>
                                <th class="px-6 py-3.5 text-center">Foto Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($recentAttendances as $att)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($att->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ $att->jam_masuk ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ $att->jam_pulang ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                        {{ $att->distance_masuk ? round($att->distance_masuk) . 'm' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeClasses = match($att->status) {
                                                'hadir' => 'bg-green-100 text-green-800',
                                                'terlambat' => 'bg-yellow-100 text-yellow-800',
                                                'izin' => 'bg-blue-100 text-blue-800',
                                                'sakit' => 'bg-purple-100 text-purple-800',
                                                'dinas' => 'bg-indigo-100 text-indigo-800',
                                                'cuti' => 'bg-cyan-100 text-cyan-800',
                                                default => 'bg-red-100 text-red-800',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                                            {{ ucfirst($att->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($att->selfie_masuk)
                                            <a href="{{ asset('storage/' . $att->selfie_masuk) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $att->selfie_masuk) }}" class="w-10 h-10 object-cover rounded-lg border border-gray-200 mx-auto hover:opacity-80 transition" alt="Selfie Masuk">
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($att->selfie_pulang)
                                            <a href="{{ asset('storage/' . $att->selfie_pulang) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $att->selfie_pulang) }}" class="w-10 h-10 object-cover rounded-lg border border-gray-200 mx-auto hover:opacity-80 transition" alt="Selfie Pulang">
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada rekaman riwayat absensi untuk pegawai ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
