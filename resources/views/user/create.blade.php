@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

<h2 class="text-xl font-semibold mb-4">Tambah User Baru</h2>

@if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
        <ul class="text-sm">
            @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('user.store') }}" method="POST"
      class="bg-white p-6 rounded-xl shadow max-w-lg">
    @csrf

    <div class="mb-4">
        <label class="block text-sm mb-1">Nama</label>
        <input type="text" name="name"
               class="w-full border rounded p-2" required>
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Email</label>
        <input type="email" name="email"
               class="w-full border rounded p-2" required>
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Password</label>
        <input type="password" name="password"
               class="w-full border rounded p-2" required>
    </div>

    <div class="mb-6">
        <label class="block text-sm mb-1">Role</label>
        <select name="role"
                class="w-full border rounded p-2">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('user.index') }}"
           class="px-4 py-2 border rounded">
            Batal
        </a>

        <button
            class="bg-blue-600 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </div>
</form>

@endsection
