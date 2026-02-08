@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<h2 class="text-xl font-semibold mb-4">Edit User</h2>

<form action="{{ route('user.update', $user->id) }}"
      method="POST"
      class="bg-white p-6 rounded-xl shadow max-w-lg">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block text-sm mb-1">Nama</label>
        <input type="text" name="name"
               value="{{ old('name', $user->name) }}"
               class="w-full border rounded p-2" required>
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Email</label>
        <input type="email" name="email"
               value="{{ old('email', $user->email) }}"
               class="w-full border rounded p-2" required>
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Password (opsional)</label>
        <input type="password" name="password"
               class="w-full border rounded p-2">
        <small class="text-gray-500">Kosongkan jika tidak diubah</small>
    </div>

    <div class="mb-6">
        <label class="block text-sm mb-1">Role</label>
        <select name="role" class="w-full border rounded p-2">
            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>
                User
            </option>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                Admin
            </option>
        </select>
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('user.index') }}"
           class="px-4 py-2 border rounded">
            Batal
        </a>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update
        </button>
    </div>
</form>

@endsection
