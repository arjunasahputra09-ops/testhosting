<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus user lama biar tidak dobel
        User::truncate();

        // Tambah user pertama (admin)
        User::create([
            'name' => 'Test User',
            'email' => 'sulpin@gmail.com',
            'password' => Hash::make('admin123'), 
        ]);

        // Tambah user kedua
        User::create([
            'name' => 'sulpin',
            'email' => 'msulpin@gmail.com',
            'password' => Hash::make('Sulpin07'), 
        ]);
    }
}
