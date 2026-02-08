<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Sistem Arsip Digital</title>
    <!-- Gunakan Tailwind Play CDN untuk development -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fallback CSS jika Tailwind CDN gagal: pastikan gambar dan layout dasar tetap responsif -->
    <style>
        /* pastikan semua gambar tidak overflow container */
        img { max-width: 100%; height: auto; display: block; }
        /* batas maksimum untuk logo khusus agar tidak terlalu besar */
        .site-logo { max-width: 160px; height: auto; margin-left: auto; margin-right: auto; }
        /* minimal layout helpers jika Tailwind tidak aktif */
        .container-center { margin: 0 auto; max-width: 56rem; }
        body { background-color: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-4xl bg-white shadow-lg rounded overflow-hidden grid grid-cols-1 md:grid-cols-2 container-center">

    <!-- PANEL KIRI -->
    <div class="hidden md:flex relative">
        <!-- background image -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url({{ asset('images/kantor-arsip.jpg') }});"></div>
        <!-- dark overlay -->
        <div class="absolute inset-0 bg-blue-700 opacity-60"></div>
        <!-- content -->
        <div class="relative z-10 flex flex-col justify-center px-10 text-white">
            <h1 class="text-lg font-serif font-semibold mb-4">SISTEM MANAJEMEN ARSIP DIGITAL</h1>
            <p class="mt-2 border-t border-white text-sm font-serif text-gray-200 leading-relaxed">
                Sistem ini digunakan untuk pengelolaan statis pada instansi pemerintah sesuai dengan kaidah kearsipan nasional.
            </p>
        </div>
    </div>

    <!-- PANEL KANAN -->
    <div class="p-10">
        <div class="text-center mb-6">
            <!-- tambahkan kelas fallback 'site-logo' untuk menjamin ukuran -->
            <img src="{{ asset('images/logo-fix.png') }}" alt="Logo Dinas Kearsipan" class="h-16 max-h-20 mx-auto mb-3 site-logo" style="max-width:160px;height:auto;">
            <h2 class="text-sm font-semi    bold text-gray-500">Dinas Kearsipan Provinsi Sumatera Selatan</h2>
        </div>


                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input type="email" name="email" required autofocus value="{{ old('email') }}" class="w-full mt-1 px-3 py-2 border rounded
                                focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <input type="password" name="password" required class="w-full mt-1 px-3 py-2 border rounded
                                focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                    <!-- CAPTCHA -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Captcha
                        </label>

                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-lg tracking-widest text-blue-700 font-bold">
                                {{ session('captcha') }}
                            </span>

                           <a href="{{ route('login') }}"
   class="inline-flex items-center justify-center
          w-9 h-9 rounded-lg border border-gray-300
          text-blue-900 hover:bg-gray-100 hover:text-blue-400
          transition">

    <svg xmlns="http://www.w3.org/2000/svg"
         class="h-4 w-4"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="1.5">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M16.023 9.348h4.992m0 0V4.356m0 4.992l-3.181-3.181A9 9 0 105.05 18.95" />
    </svg>
</a>

                        </div>

                        <input type="text" name="captcha" placeholder="Masukkan captcha" class="w-full px-3 py-2 border rounded
                    focus:ring-2 focus:ring-blue-600 focus:outline-none" required>
                    </div>




                    @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 via-blue-500 to-blue-900 text-white font-semibold px-6 py-2 rounded-lg shadow-lg
                            hover:shadow-x1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 ">
                        Masuk
                    </button>
                </form>

                <p class="text-xs text-center text-gray-500 mt-6">
                    © {{ date('Y') }} Dinas Kearsipan Provinsi Sumatera Selatan
                </p>
            </div>

        </div>

    </body>

    </html>
