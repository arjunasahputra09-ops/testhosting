@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<!-- ===================== BREADCRUMB ===================== -->
<div class="mb-4 text-sm text-gray-400">
    Home / Dashboard Admin
</div>

<!-- ===================== WRAPPER ===================== -->
<div id="dashboard" class="opacity-0 translate-y-3 transition-all duration-700">

    <!-- ===================== HEADER ===================== -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard Admin</h2>
        <p class="text-sm text-gray-500">
            Ringkasan pengawasan dan pengelolaan sistem arsip digital
        </p>
    </div>

    <!-- ===================== STATISTIK UTAMA ===================== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <!-- TOTAL USER -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
            <a href="{{ route('user.index') }}"
            <p class="text-sm text-gray-500">Total User</p>
            <h3 class="text-3xl font-bold text-blue-600 mt-1">
                {{ $totalUser }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">
                Pengguna aktif sistem
            </p>
            </a>
        </div>

        <!-- ARSIP MENUNGGU VERIFIKASI -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
            <a href="{{ route('arsip.verifikasi') }}">
            <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
            <h3 class="text-3xl font-bold text-yellow-500 mt-1">
                {{ $arsipMenunggu }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">
                Arsip belum disetujui admin
            </p>
            </a>
        </div>

        <!-- ARSIP DISETUJUI -->
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-sm text-gray-500">Arsip Disetujui</p>
            <h3 class="text-3xl font-bold text-green-600 mt-1">
                {{ $arsipDisetujui }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">
                Arsip statis tervalidasi
            </p>
        </div>

    </div>

    <!-- ===================== AKSI ADMIN ===================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

        <!-- VERIFIKASI ARSIP -->
        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <h3 class="text-sm font-semibold mb-2">Verifikasi Arsip</h3>
            <p class="text-xs text-gray-500 mb-4">
                Tinjau dan setujui arsip yang diajukan user
            </p>

            <a href="{{ route('arsip.verifikasi') }}"
               class="inline-block bg-yellow-500 hover:bg-yellow-600
                      text-white text-sm px-4 py-2 rounded transition">
                Lihat Arsip Menunggu
            </a>
        </div>

        <!-- MANAJEMEN USER -->
        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <h3 class="text-sm font-semibold mb-2">Manajemen User</h3>
            <p class="text-xs text-gray-500 mb-4">
                Kelola akun dan hak akses pengguna
            </p>

            <a href="{{ route('user.index') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700
                      text-white text-sm px-4 py-2 rounded transition">
                Kelola User
            </a>
        </div>

    </div>

    <!-- ===================== CATATAN ADMIN ===================== -->
    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl">
        <h4 class="text-sm font-semibold text-blue-700 mb-1">
            Catatan
        </h4>
        <p class="text-xs text-blue-600 leading-relaxed">
            Dashboard ini digunakan oleh admin untuk memantau aktivitas sistem,
            melakukan verifikasi arsip statis, serta mengelola pengguna.
            Admin tidak melakukan input arsip secara langsung.
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.getElementById('dashboard')
            .classList.remove('opacity-0','translate-y-3');
    }, 100);
});
</script>
@endpush
