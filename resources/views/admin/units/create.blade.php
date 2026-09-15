<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Tambah Unit / Bagian Kerja') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Dinas Kearsipan dan Perpustakaan Kabupaten Subang</p>
            </div>
            <a href="{{ route('admin.units.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
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
                <form action="{{ route('admin.units.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Kode Unit -->
                    <div>
                        <label for="kode" class="block text-sm font-semibold text-gray-700">
                            Kode Unit <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode') }}" required placeholder="Contoh: DISARPUS-SBG, BID-ARSIP, BID-PUS" class="mt-1 block w-full rounded-lg border-gray-300 uppercase shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('kode') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Kode unik identifikasi unit atau bidang kerja.</p>
                        @error('kode')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Unit -->
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700">
                            Nama Unit / Bidang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required placeholder="Contoh: Bidang Penyelenggaraan Kearsipan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat / Lokasi Kerja -->
                    <div>
                        <label for="alamat" class="block text-sm font-semibold text-gray-700">
                            Alamat / Keterangan Lokasi
                        </label>
                        <textarea name="alamat" id="alamat" rows="3" placeholder="Contoh: Jl. MT Haryono No. 9, Kel. Soklat, Kec. Subang" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700">
                            Status Operasional <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                            <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Aktif (Dapat digunakan untuk penempatan pegawai)</option>
                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.units.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Simpan Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
