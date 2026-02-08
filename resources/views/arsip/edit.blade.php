@extends('layouts.app')

@section('title', 'Edit Arsip')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Arsip: {{ $arsip->uraian_masalah }}</h2>

        <form action="{{ route('arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Baris 1: No Urut & Pencipta Arsip -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="no_urut" class="block text-sm font-medium text-gray-700 mb-1">No. Urut</label>
                    <input type="text" id="no_urut" name="no_urut" class="w-full rounded-lg shadow-sm border-gray-300" value="{{ old('no_urut', $arsip->no_urut) }}">
                </div>
                <div>
                    <label for="pencipta_arsip" class="block text-sm font-medium text-gray-700 mb-1">Pencipta Arsip</label>
                    <input type="text" id="pencipta_arsip" name="pencipta_arsip" class="w-full rounded-lg shadow-sm border-gray-300" value="{{ old('pencipta_arsip', $arsip->pencipta_arsip) }}">
                </div>
            </div>

            <!-- Baris 2: Kode Klasifikasi & Jenis Arsip -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="kode_klasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Kode Klasifikasi <span class="text-red-500">*</span></label>
                    <input type="text" id="kode_klasifikasi" name="kode_klasifikasi" class="w-full rounded-lg shadow-sm border-gray-300" value="{{ old('kode_klasifikasi', $arsip->kode_klasifikasi) }}" required>
                </div>
                <div>
                    <label for="jenis_arsip" class="block text-sm font-medium text-gray-700 mb-1">Jenis Arsip <span class="text-red-500">*</span></label>
                    <select id="jenis_arsip" name="jenis_arsip" class="w-full rounded-lg shadow-sm border-gray-300" required>
                        <option value="">-- Pilih Jenis Arsip --</option>
                        <option value="Dokumen Legal" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'Dokumen Legal' ? 'selected' : '' }}>Dokumen Legal</option>
                        <option value="Laporan" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                        <option value="Foto/Video" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'Foto/Video' ? 'selected' : '' }}>Foto/Video</option>
                        <option value="Surat" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'Surat' ? 'selected' : '' }}>Surat</option>
                        <option value="Lainnya" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <!-- Baris 3: Uraian Masalah -->
            <div class="mb-4">
                <label for="uraian_masalah" class="block text-sm font-medium text-gray-700 mb-1">Uraian Masalah <span class="text-red-500">*</span></label>
                <textarea id="uraian_masalah" name="uraian_masalah" rows="3" class="w-full rounded-lg shadow-sm border-gray-300" required>{{ old('uraian_masalah', $arsip->uraian_masalah) }}</textarea>
            </div>

            <!-- Baris 4: Kurun Waktu & Tingkat Perkembangan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="kurun_waktu" class="block text-sm font-medium text-gray-700 mb-1">Kurun Waktu <span class="text-red-500">*</span></label>
                    <input type="text" id="kurun_waktu" name="kurun_waktu" class="w-full rounded-lg shadow-sm border-gray-300" value="{{ old('kurun_waktu', $arsip->kurun_waktu) }}" placeholder="Contoh: 2023 atau 2020-2023" required>
                </div>
                <div>
                    <label for="tingkat_perkembangan" class="block text-sm font-medium text-gray-700 mb-1">Tingkat Perkembangan</label>
                    <select id="tingkat_perkembangan" name="tingkat_perkembangan" class="w-full rounded-lg shadow-sm border-gray-300">
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="Asli" {{ old('tingkat_perkembangan', $arsip->tingkat_perkembangan) == 'Asli' ? 'selected' : '' }}>Asli</option>
                        <option value="Salinan" {{ old('tingkat_perkembangan', $arsip->tingkat_perkembangan) == 'Salinan' ? 'selected' : '' }}>Salinan</option>
                    </select>
                </div>
            </div>

            <!-- Baris 5: Jumlah Berkas & No Boks -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="jumlah_berkas" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Berkas</label>
                    <input type="text" id="jumlah_berkas" name="jumlah_berkas" class="w-full rounded-lg shadow-sm border-gray-300" value="{{ old('jumlah_berkas', $arsip->jumlah_berkas) }}" placeholder="Contoh: 1 Berkas / 2 Lembar">
                </div>
                <div>
                    <label for="no_boks" class="block text-sm font-medium text-gray-700 mb-1">No. Boks</label>
                    <input type="text" id="no_boks" name="no_boks" class="w-full rounded-lg shadow-sm border-gray-300" value="{{ old('no_boks', $arsip->no_boks) }}">
                </div>
            </div>

            <!-- Baris 6: Keterangan -->
            <div class="mb-4">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="2" class="w-full rounded-lg shadow-sm border-gray-300">{{ old('keterangan', $arsip->keterangan) }}</textarea>
            </div>

            <!-- Baris 7: Upload File -->
            <div class="mb-6">
                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload File (Opsional)</label>
                <input type="file" id="file" name="file" class="w-full border border-gray-300 rounded-lg p-2 file:mr-4 
                file:py-2 file:px-4 
                file:rounded-lg 
                file:border-0 file:text-sm 
                file:font-semibold file:bg-blue-50
                file:text-blue-700 hover:file:bg-blue-100">
                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah file.</small>
                @if ($arsip->file)
                    <p class="text-sm text-gray-600 mt-2">File saat ini: <a href="{{ route('arsip.download', $arsip->id) }}" class="text-blue-600 hover:underline">{{ basename($arsip->file) }}</a></p>
                @endif
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end">
    <button type="submit"
        class="flex items-center gap-2
               bg-[#2e507c] text-white
               px-6 py-2 rounded-lg font-medium
               hover:bg-[#3a4d7a] hover:shadow-lg hover:scale-[1.02]
               transition-all duration-200
               focus:outline-none focus:ring-2 focus:ring-[#496294] focus:ring-offset-2">

        <!-- Icon Save -->
          <svg xmlns="http://www.w3.org/2000/svg"
       class="w-5 h-5 transition-transform duration-200 group-hover:scale-110"
       fill="none"
       viewBox="0 0 24 24"
       stroke="currentColor"
       stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M17 16v2a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h2" />
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M7 12l5-5 5 5M12 7v12" />
  </svg>

        <span>Update</span>
    </button>
</div>
        </form>
    </div>
</div>
@endsection

