@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- ===================== HEADER ===================== -->
<div class="mb-4 text-sm text-gray-400 animate-fade-in">
    Home / Dashboard
</div>

<!-- ===================== DASHBOARD WRAPPER ===================== -->
<div id="dashboard" class="opacity-0 translate-y-3 transition-all duration-700">

<!-- ===================== STATISTIK CARDS ===================== -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

@php
$cards = [
    ['title'=>'Total Arsip','value'=>$totalArsip,'icon'=>'folder','color'=>'bg-blue-600'],
    ['title'=>'Arsip Digital','value'=>$arsip_app,'icon'=>'storage','color'=>'bg-emerald-600'],
    ['title'=>'Arsip Fisik','value'=>$arsipFisik,'icon'=>'fisik','color'=>'bg-yellow-500'],
    ['title'=>'Tahun Ini','value'=>$arsipTahunIni,'icon'=>'calendar','color'=>'bg-sky-500'],
];
@endphp

@foreach($cards as $card)
<div class="bg-[#2e507c] border border-[#3a506a] p-4 rounded-xl flex justify-between
            shadow-sm hover:shadow-xl hover:scale-[1.03]
            transition-all duration-300">

    <div>
        <p class="text-xs text-gray-200">{{ $card['title'] }}</p>
        <h2 class="text-2xl font-bold text-white mt-1">
            {{ $card['value'] }}
        </h2>
    </div>

    <div class="{{ $card['color'] }} p-2 rounded-lg shadow">
        {{-- TOTAL ARSIP --}}
        @if($card['icon']=='folder')
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 7h5l2 3h11v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
        </svg>

        {{-- ARSIP DIGITAL (STORAGE / MEMORI) --}}
        @elseif($card['icon']=='storage')
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
        </svg>

        {{-- ARSIP FISIK (DOKUMEN) --}}
        @elseif($card['icon']=='fisik')
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M7 3h6l4 4v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
        </svg>

        {{-- TAHUN INI --}}
        @else
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 7V3m8 4V3M4 11h16M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        @endif
    </div>
</div>
@endforeach
</div>

<!-- ===================== GRAFIK ===================== -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

<div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
    <h3 class="text-sm text-gray-500 font-semibold mb-3">Distribusi Jenis Arsip</h3>
    <canvas id="jenisArsipChart" height="140"></canvas>
</div>

<div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
    <h3 class="text-sm text-gray-500 font-semibold mb-3">Distribusi Media Arsip</h3>
    <canvas id="mediaArsipChart" height="140"></canvas>
</div>

</div>

<!-- ===================== ARSIP TERBARU ===================== -->
<div class="bg-white p-4 rounded-xl shadow mb-6">

<h3 class="text-sm font-semibold mb-1">Arsip Terbaru</h3>
<p class="text-xs text-gray-500 mb-4">5 arsip terakhir</p>

<div class="space-y-3">
@forelse ($arsipTerbaru as $arsip)
<div class="flex gap-3 p-3 rounded-lg hover:bg-gray-50 hover:shadow-sm transition">

    <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
        {{-- DOCUMENT TEXT --}}
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12h6m-6 4h6M7 3h8l4 4v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
        </svg>
    </div>

    <div class="flex-1">
        <p class="text-sm font-semibold text-gray-800">
            {{ Str::limit($arsip->uraian_masalah, 60) }}
        </p>
        <p class="text-xs text-gray-500">{{ $arsip->pencipta_arsip ?? '-' }}</p>
        <p class="text-[10px] text-gray-400 mt-1">
            {{ $arsip->kode_klasifikasi }} • {{ $arsip->created_at->format('d-m-Y') }}
        </p>
    </div>
</div>
@empty
<p class="text-xs text-gray-500 text-center">Belum ada arsip</p>
@endforelse
</div>
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

<script>
new Chart(document.getElementById('jenisArsipChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($grafikJenis->keys()) !!},
        datasets: [{
            data: {!! json_encode($grafikJenis->values()) !!},
            backgroundColor: ['#ef4444','#f59e0b','#8b5cf6']
        }]
    },
    options: { plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});

new Chart(document.getElementById('mediaArsipChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($grafikMedia->keys()) !!},
        datasets: [{
            data: {!! json_encode($grafikMedia->values()) !!},
            backgroundColor: ['#10b981','#3b82f6']
        }]
    },
    options: { plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});
</script>

@endpush
