<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Data Pegawai PPPK') }}
            </h2>
            <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 md:p-8">

                <form method="POST" action="{{ route('admin.employees.update', $employee->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Section Data Pegawai -->
                    <div>
                        <h3 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                            Data Kepegawaian
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama Lengkap -->
                            <div class="sm:col-span-2">
                                <label for="nama" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    Nama Lengkap Beserta Gelar <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama', $employee->nama) }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nama') border-red-500 @enderror">
                                @error('nama')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIP -->
                            <div>
                                <label for="nip" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    NIP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nip" id="nip" value="{{ old('nip', $employee->nip) }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nip') border-red-500 @enderror">
                                @error('nip')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor PPPK -->
                            <div>
                                <label for="nomor_pppk" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    Nomor SK / Kartu PPPK
                                </label>
                                <input type="text" name="nomor_pppk" id="nomor_pppk" value="{{ old('nomor_pppk', $employee->nomor_pppk) }}" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('nomor_pppk')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jabatan -->
                            <div>
                                <label for="jabatan" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    Jabatan Fungsional
                                </label>
                                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $employee->jabatan) }}" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('jabatan')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit Kerja -->
                            <div>
                                <label for="unit_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    Unit / Bagian Kerja <span class="text-red-500">*</span>
                                </label>
                                <select name="unit_id" id="unit_id" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('unit_id') border-red-500 @enderror">
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id', $employee->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Kepegawaian -->
                            <div>
                                <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    Status Kepegawaian <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="1" {{ old('status', $employee->status ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status', $employee->status ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Foto Profil -->
                            <div class="sm:col-span-2">
                                <label for="foto" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    Ganti Foto Profil (Opsional, Maks 2MB)
                                </label>
                                <div class="flex items-center space-x-4 mt-2">
                                    @if($employee->foto)
                                        <img src="{{ asset('storage/' . $employee->foto) }}" class="w-14 h-14 rounded-full object-cover border border-gray-200" alt="{{ $employee->nama }}">
                                    @endif
                                    <input type="file" name="foto" id="foto" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                                @error('foto')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section Akun Login -->
                    <div>
                        <h3 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                            Akun Login Sistem
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    Email Login <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $employee->user->email ?? '') }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Baru -->
                            <div>
                                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                                    Ubah Password Baru (Kosongkan jika tidak diubah)
                                </label>
                                <input type="password" name="password" id="password" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-500 @enderror" placeholder="Minimal 6 karakter">
                                @error('password')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-4 border-t border-gray-200 flex justify-end space-x-3">
                        <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
