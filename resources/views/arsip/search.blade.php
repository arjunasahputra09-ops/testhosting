@extends('layouts.app')

@section('title', 'Pencarian Arsip')

@section('content')

@php
    function highlight($text, $keyword) {
        if (!$keyword) return e($text);
        return preg_replace(
            '/(' . preg_quote($keyword, '/') . ')/i',
            '<mark class="bg-yellow-300 px-1 rounded">$1</mark>',
            e($text)
        );
    }

    // pastikan view punya variabel $query untuk kompatibilitas
    // Ambil dari $q (legacy), $query (controller), atau request('q') (query string)
    $query = $q ?? $query ?? request()->get('q', '');
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-xl font-semibold text-gray-800">Pencarian Arsip</h1>
        <p class="text-sm text-gray-500">Cari arsip berdasarkan kata kunci</p>
    </div>

    <!-- FORM SEARCH -->
    <div class="bg-white border rounded-xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-1">Form Pencarian</h2>
        <p class="text-xs text-gray-500 mb-4">Masukkan kata kunci arsip</p>

        <form action="{{ route('arsip.search') }}" method="GET" id="searchForm">
            <div class="flex gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <!-- ICON TETAP -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </span>

                    <!-- gunakan $query yang sudah di-set di atas -->
                    <input type="text" name="q" value="{{ $query }}"
                        placeholder="Cari judul, kode, pencipta..."
                        class="w-full pl-10 pr-4 py-2 text-sm border rounded-lg
                               focus:ring-blue-500 focus:border-blue-500">
                </div>

                <button type="submit"
    class="px-3 py-2 bg-[#2e507c] text-white rounded-lg
           hover:bg-blue-800 transition flex items-center justify-center hover:scale-105 shadow-sm hover:shadow-md
"
    title="Cari">
    <!-- ICON SEARCH (TETAP ICON ASLI) -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
         fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
    </svg>
</button>

            </div>
        </form>
    </div>

    <!-- RESULT -->
    <div class="bg-white border rounded-xl shadow-sm relative overflow-hidden">

        <!-- INFO JUMLAH -->
        @if(!empty($query) && !$arsips->isEmpty())
            <div class="px-6 py-3 border-b text-sm text-gray-600">
                Menampilkan
                <span class="font-semibold">{{ $arsips->total() }}</span>
                hasil untuk
                <span class="font-semibold text-gray-800">"{{ $query }}"</span>
            </div>
        @endif

        <!-- SKELETON -->
        <div id="skeleton" class="hidden p-6 space-y-4">
            @for($i=0;$i<6;$i++)
                <div class="h-4 bg-gray-200 rounded animate-pulse"></div>
            @endfor
        </div>

        <!-- CONTENT -->
        <div id="realContent">

            @if(empty($query))
                <div class="py-20 text-center text-gray-500">
                    <p class="font-medium">Masukkan kata kunci pencarian</p>
                </div>

            @elseif($arsips->isEmpty())
                <div class="py-16 text-center text-gray-500">
                    Arsip dengan kata kunci
                    <span class="font-semibold">"{{ $query }}"</span>
                    tidak ditemukan
                </div>

            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Kode</th>
                                <th class="px-6 py-3 text-left">Uraian</th>
                                <th class="px-6 py-3 text-left">Pencipta</th>
                                <th class="px-6 py-3 text-left">Kurun</th>
                                <th class="px-6 py-3 text-left">Boks</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach($arsips as $arsip)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3">{{ $arsip->no_urut }}</td>
                                <td class="px-6 py-3 font-medium">
                                    {!! highlight($arsip->kode_klasifikasi,$query) !!}
                                </td>
                                <td class="px-6 py-3">
                                    {!! highlight($arsip->uraian_masalah,$query) !!}
                                </td>
                                <td class="px-6 py-3 font-medium">
                                    {!! highlight($arsip->pencipta_arsip,$query) !!}
                                </td>
                                <td class="px-6 py-3">{{ $arsip->kurun_waktu }}</td>
                                <td class="px-6 py-3">{{ $arsip->no_boks }}</td>


                                <!-- AKSI -->
                                <td class="px-6 py-3 text-center">
    <div class="flex justify-center gap-3">

        {{-- DOWNLOAD --}}
        @if($arsip->file)
        <a href="{{ route('arsip.download',$arsip->id) }}"
           class="icon-action text-gray-500 hover:text-blue-600"
           title="Download">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                 fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/>
            </svg>
        </a>
        @endif
        {{-- VIEW --}}
        @if(auth()->check() && auth()->user()->role === 'user' && $arsip->file)

        {{-- EDIT --}}
        <a href="{{ route('arsip.edit',$arsip->id) }}"
           class="icon-action text-indigo-600 hover:text-indigo-800"
           title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                 fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.862 4.487l2.651 2.651M7 17l4-1 9-9-3-3-9 9-1 4z"/>
            </svg>
        </a>

        {{-- HAPUS --}}
        <form action="{{ route('arsip.destroy',$arsip->id) }}"
              method="POST" id="delete-{{ $arsip->id }}">
            @csrf
            @method('DELETE')
            <button type="button"
                onclick="confirmDelete({{ $arsip->id }})"
                class="icon-action text-red-600 hover:text-red-700"
                title="Hapus">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                     fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 7l-.867 12.142A2 2 0
                             0116.138 21H7.862a2
                             2 0 01-1.995-1.858
                             L5 7"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M10 11v6M14 11v6"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 7V4a1 1 0
                             011-1h4a1
                             1 0 011 1v3"/>
                </svg>
            </button>
        </form>

        @endif
    

    </div>
</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t">
                    {{ $arsips->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- MICRO INTERACTION -->
<style>
.icon-action{
    transition: all .2s ease;
}
.icon-action:hover{
    transform: scale(1.15);
    filter: drop-shadow(0 6px 8px rgba(0,0,0,.25));
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id){
    Swal.fire({
        title: 'Hapus Arsip?',
        text: 'Data tidak dapat dikembalikan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Hapus'
    }).then(r=>{
        if(r.isConfirmed){
            document.getElementById('delete-'+id).submit()
        }
    })
}

// skeleton loading
document.querySelectorAll('form, a.page-link').forEach(el=>{
    el.addEventListener('click',()=>{
        document.getElementById('skeleton').classList.remove('hidden')
        document.getElementById('realContent').classList.add('hidden')
    })
})
</script>

@endsection
