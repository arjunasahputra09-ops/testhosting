@extends('layouts.app')

@section('title', 'Daftar Arsip')

@section('content')

<!-- ===================== WRAPPER ===================== -->
<div id="pageContent"
     class="max-w-7xl mx-auto bg-white rounded-xl shadow-sm border border-sky-100
            opacity-0 translate-y-3 transition-all duration-700">

    <!-- ===================== HEADER ===================== -->
    <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="text-xl font-bold text-gray-800">
            Daftar Arsip
        </h2>
@auth
@if(auth()->user()->role === 'user')

        
            @php
                $query = request()->query();
                $qs = count($query) ? ('?'.http_build_query($query)) : '';
            @endphp
            <a href="{{ route('arsip.export.csv') }}{{ $qs }}"
               class="flex items-center gap-2 bg-emerald-600 text-white
                      px-4 py-2 rounded-lg text-sm
                      hover:bg-emerald-700 hover:shadow-md
                      transition"
               title="Ekspor arsip ke CSV">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M12 3v12m0 0l-4-4m4 4l4-4M4 17h16"/>
                </svg>
                Export CSV
            </a>
@endif
@endauth
    </div>

    <!-- ===================== Arsip Saya (jika ada) ===================== -->
    @if(isset($myArsips) && $myArsips->count())
            <table class="min-w-full text-sm">
                <thead class="bg-[#496294] text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Kode Arsip</th>
                        <th class="px-6 py-3 text-left">Uraian Masalah</th>
                        <th class="px-6 py-3 text-left">Pencipta</th>
                        <th class="px-6 py-3 text-left">Jenis</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($myArsips as $arsip)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            {{ $arsip->no_urut ?? '-' }} {{-- <-- fallback ketika no_urut null --}}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $arsip->kode_klasifikasi }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ Str::limit($arsip->uraian_masalah, 80) }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $arsip->pencipta_arsip ?? '-' }} {{-- sudah ada, tetap pakai fallback --}}
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $arsip->jenis_arsip }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-4">
                                {{-- DOWNLOAD: pemilik atau admin atau jika approved --}}
                                @if($arsip->file && ( $arsip->status === 'approved' || (auth()->check() && (auth()->user()->role === 'admin' || $arsip->user_id === auth()->id())) ))
                                <a href="{{ route('arsip.download', $arsip->id) }}" class="text-gray-400 hover:text-blue-600 hover:scale-110 transition" title="Download Arsip">
                                    <!-- icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057 -5.064 7-9.542 7 -4.477 0-8.268-2.943 -9.542-7z"/></svg>
                                </a>
                                @endif

                                {{-- EDIT & DELETE: pemilik atau admin --}}
                                @if(auth()->check() && (auth()->user()->role === 'admin' || $arsip->user_id === auth()->id()))
                                <a href="{{ route('arsip.edit', $arsip->id) }}" class="text-gray-400 hover:text-indigo-600 hover:scale-110 transition" title="Edit Arsip">
                                    <!-- edit icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11 a2 2 0 002 2h11 a2 2 0 002-2v-5"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M18.586 2.586a2 2 0 112.828 2.828L11 15l-4 1 1-4 10.586-10.586z"/></svg>
                                </a>

                                <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete text-red-500 hover:text-red-600 hover:scale-110 transition" title="Hapus Arsip">
                                        <!-- delete icon -->
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858 L5 7"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
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

        <div class="flex justify-between items-center px-6 py-4 border-t">
            <p class="text-sm text-gray-600">
                Menampilkan {{ $myArsips->firstItem() }}–{{ $myArsips->lastItem() }} dari {{ $myArsips->total() }} 
            </p>
            {{ $myArsips->links('pagination.custom') }}
        </div>
    </div>
    @endif
    
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.getElementById('pageContent')
            .classList.remove('opacity-0','translate-y-3');
    }, 100);
});
</script>

@if(auth()->check())
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('form');

        Swal.fire({
            title: 'Hapus Arsip?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endif
@endpush
