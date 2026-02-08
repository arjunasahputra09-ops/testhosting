@extends('layouts.app')

@section('title', 'Import Arsip dari CSV')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Import Arsip dari CSV</h2>

    {{-- tampilkan pesan sukses --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Upload CSV -->
    <form action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="import_file" class="block text-sm font-medium text-gray-700 mb-1">Upload File CSV / XLSX</label>

            <!-- ubah name menjadi import_file sesuai validasi controller -->
            <input type="file" id="import_file" name="import_file"
                   class="w-full border border-gray-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                   required accept=".csv,.xlsx">

            @error('import_file')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p class="font-bold">Perhatian!</p>
            <ul class="list-disc list-inside text-sm">
                <li>Pastikan file Anda berformat <strong>.csv</strong> atau <strong>.xlsx</strong>.</li>
                <li>Pastikan urutan kolom di CSV Anda adalah: <br>
                    <code class="text-xs">No, Pencipta Arsip, Kode Klasifikasi, Jenis Arsip, Uraian Masalah, Kurun Waktu, Tingkat Perkembangan, Jumlah Berkas, No Boks, Keterangan</code>
                </li>
            </ul>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Import Data
            </button>
        </div>
    </form>
</div>
@endsection

