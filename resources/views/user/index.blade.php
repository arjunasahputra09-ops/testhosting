@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')

{{-- Flash Message --}}
@if (session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
        {{ session('error') }}
    </div>
@endif

{{-- Header --}}
<div class="flex justify-between items-center mb-4">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">
            Manajemen User
        </h2>
        <p class="text-sm text-gray-500">
            Daftar pengguna yang terdaftar dalam sistem
        </p>
    </div>

    <a href="{{ route('user.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
        + Tambah User
    </a>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            <tr>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-center">Role</th>
                <th class="p-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="p-3">
                        {{ $user->name }}
                    </td>

                    <td class="p-3">
                        {{ $user->email }}
                    </td>

                    <td class="p-3 text-center">
                        <span class="px-2 py-1 rounded text-xs
                            {{ $user->role === 'admin'
                                ? 'bg-blue-100 text-blue-700'
                                : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    <td class="p-3 text-center space-x-2">
                        <a href="{{ route('user.edit', $user->id) }}"
                           class="text-blue-600 hover:underline text-sm">
                            Edit
                        </a>

                        <form action="{{ route('user.destroy', $user->id) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:underline text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"
                        class="p-4 text-center text-gray-500">
                        Belum ada user terdaftar
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
