<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Jadwal Kerja') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">{{ $schedule->nama }}</p>
            </div>
            <a href="{{ route('admin.work-schedules.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 sm:p-8">
                <form action="{{ route('admin.work-schedules.update', $schedule) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Jadwal -->
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700">
                            Nama Jadwal Kerja <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $schedule->nama) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Masuk & Jam Pulang -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="jam_masuk" class="block text-sm font-semibold text-gray-700">
                                Jam Masuk <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_masuk" id="jam_masuk" value="{{ old('jam_masuk', substr($schedule->jam_masuk, 0, 5)) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('jam_masuk') border-red-500 @enderror">
                            @error('jam_masuk')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jam_pulang" class="block text-sm font-semibold text-gray-700">
                                Jam Pulang <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_pulang" id="jam_pulang" value="{{ old('jam_pulang', substr($schedule->jam_pulang, 0, 5)) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('jam_pulang') border-red-500 @enderror">
                            @error('jam_pulang')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Toleransi Keterlambatan -->
                    <div>
                        <label for="toleransi_terlambat" class="block text-sm font-semibold text-gray-700">
                            Toleransi Keterlambatan (menit) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" min="0" max="120" name="toleransi_terlambat" id="toleransi_terlambat" value="{{ old('toleransi_terlambat', $schedule->toleransi_terlambat) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('toleransi_terlambat') border-red-500 @enderror">
                        @error('toleransi_terlambat')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilihan Hari Kerja Aktif -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Hari Kerja Aktif
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200">
                            @php
                                $dayList = [
                                    'senin' => 'Senin',
                                    'selasa' => 'Selasa',
                                    'rabu' => 'Rabu',
                                    'kamis' => 'Kamis',
                                    'jumat' => 'Jumat',
                                    'sabtu' => 'Sabtu',
                                    'minggu' => 'Minggu',
                                ];
                            @endphp

                            @foreach($dayList as $key => $label)
                                <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                                    <input type="checkbox" name="{{ $key }}" value="1" {{ old($key, $schedule->$key ? '1' : '0') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700">
                            Status Jadwal <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                            <option value="1" {{ old('status', $schedule->status ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $schedule->status ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.work-schedules.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Perbarui Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
