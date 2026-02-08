@extends('layouts.app')

@section('title', 'Verifikasi Arsip')

@section('content')

<!-- ===================== HEADER ===================== -->
<div class="mb-6">
    <h2 class="text-xl font-semibold text-gray-800">
        Verifikasi Arsip Statis
    </h2>   
    <p class="text-sm text-gray-500">
        Arsip yang telah diinput oleh petugas dan menunggu peninjauan admin
    </p>
</div>

<!-- ===================== ALERT (TETAP ADA, TIDAK DIHAPUS) ===================== -->
@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300
                text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

<!-- ===================== TABLE ===================== -->
<div class="bg-white rounded-xl shadow overflow-x-auto">

    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">Uraian Masalah</th>
                <th class="px-4 py-3 text-center">Kode Klasifikasi</th>
                <th class="px-4 py-3 text-center">Tanggal Input</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y">

            @forelse($arsips as $arsip)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">
                        {{ $arsip->uraian_masalah }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        {{ $arsip->kode_klasifikasi }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        {{ $arsip->created_at->format('d-m-Y') }}
                    </td>

                    <!-- STATUS BADGE -->
                    <td class="px-4 py-3 text-center">
                        @if($arsip->status === 'pending')
                            <span class="inline-block bg-yellow-100 text-yellow-700
                                        px-3 py-1 rounded-full text-xs font-semibold">
                                Pending
                            </span>
                        @else
                            <span class="inline-block bg-green-100 text-green-700
                                        px-3 py-1 rounded-full text-xs font-semibold">
                                Approved
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="px-4 py-3 text-center">
                        @if($arsip->status === 'pending')
                            <form method="POST"
                                  action="{{ route('arsip.verifikasi.approve', $arsip->id) }}"
                                  class="form-verifikasi">
                                @csrf
                                @method('PUT')

                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700
                                           text-white text-xs font-semibold
                                           px-3 py-2 rounded transition">
                                    Tandai Terverifikasi
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs italic">
                                Sudah diverifikasi
                            </span>
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5"
                        class="px-4 py-6 text-center text-gray-500">
                        Tidak ada arsip yang perlu diverifikasi
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>

</div>

@endsection

<!-- ===================== SCRIPT SWEETALERT (DITAMBAHKAN, TIDAK MENGGANGGU KODE LAIN) ===================== -->
@push('scripts')
<script>
document.querySelectorAll('.form-verifikasi').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Verifikasi Arsip?',
            text: 'Arsip yang sudah diverifikasi tidak dapat dikembalikan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Verifikasi',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#16a34a',
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


@endpush
