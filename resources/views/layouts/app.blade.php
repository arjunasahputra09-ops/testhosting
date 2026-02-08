<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Arsip Digital')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</head>

<body class="bg-gray-100">

<!-- ===================== TOPBAR ===================== -->
<header class="relative shadow">
    <div class="absolute inset-0">
        <img src="{{ asset('images/header-kantor.jpg') }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#1e3a5f]/80 to-[#1e3a5f]/60"></div>
    </div>

    <div class="relative z-10 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo-fix.png') }}" class="h-14">

            <div>
                <h1 class="font-semibold text-white font-serif text-lg">
                    SISTEM MANAJEMEN ARSIP DIGITAL
                </h1>
                <p class="text-sm font-serif  text-gray-200">
                    PROV.SUMATERA SELATAN
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-100">
                Login sebagai <b>{{ ucfirst(Auth::user()->role) }}</b>
            </span>

            <button onclick="confirmLogout()"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm transition">
                Logout
            </button>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</header>

<!-- ===================== WRAPPER ===================== -->
<div class="flex min-h-[calc(100vh-80px)]">

<!-- ===================== SIDEBAR ===================== -->
<aside id="sidebar" class="w-64 bg-[#1e3a5f] text-white flex flex-col transition-all duration-300">
    <div class="px-4 py-3 border-b border-white text-sm font-semibold flex justify-between items-center">
        <span class="sidebar-title">MENU</span>

        
        <button id="btn-toggle-sidebar" class="p-1 rounded inline-flex bg-white/10 hover:bg-white/20" title="Toggle sidebar">
           <svg id="mini-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"> <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/> </svg>
        </button>
    </div>

    <nav class="flex-1 p-2 space-y-1 text-sm">

        <a href="{{ auth()->user()->role === 'admin' ? route('dashboard.admin') : route('dashboard.user') }}"
           title="Dashboard"
           class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800 transition">
            <span class="menu-text">Dashboard</span>
            <i data-lucide="layout-dashboard" class="nav-icon w-5 h-5 ml-auto"></i>
        </a>

        @if(Auth::user()->role === 'user')
        <a href="{{ route('arsip.create') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span class="menu-text">Input Arsip</span>
            <i data-lucide="file-plus" class="nav-icon w-5 h-5 ml-auto"></i>
        </a>

        <a href="{{ route('arsip.import.form') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span class="menu-text">Import Arsip</span>
            <i data-lucide="upload" class="nav-icon w-5 h-5 ml-auto"></i>
        </a>

        <a href="{{ route('arsip.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span class="menu-text">Data Arsip</span>
            <i data-lucide="folder-open" class="nav-icon w-5 h-5 ml-auto"></i>
        </a>

        <a href="{{ route('arsip.search') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span class="menu-text">Pencarian Arsip</span>
            <i data-lucide="search" class="nav-icon w-5 h-5 ml-auto"></i>
        </a>
        @endif

        @if(Auth::user()->role === 'admin')
        <a href="{{ route('arsip.verifikasi') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span class="menu-text">Verifikasi Arsip</span>
            <i data-lucide="badge-check" class="nav-icon w-5 h-5 ml-auto"></i>
        </a>

        <a href="{{ route('user.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-800">
            <span class="menu-text">Kelola User</span>
            <i data-lucide="users" class="nav-icon w-5 h-5 ml-auto"></i>
        </a>
        @endif

    </nav>
</aside>

<!-- ===================== CONTENT ===================== -->
<main class="flex-1 p-6">
    @yield('content')
</main>

</div>

@stack('scripts')

<!-- Sidebar collapse script (fixed) -->
<script>
(function(){
    const sidebar = document.getElementById('sidebar');
    const btn = document.getElementById('btn-toggle-sidebar');
    const iconLeft = document.getElementById('icon-collapse-left');
    const iconRight = document.getElementById('icon-collapse-right');
    const menuTexts = () => document.querySelectorAll('#sidebar .menu-text');
    const sidebarTitle = () => document.querySelector('.sidebar-title');

    // ensure lucide icons are created before manipulating nav icons
    if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }

    function getNavIcons() {
        return document.querySelectorAll('#sidebar .nav-icon');
    }

    function applyCollapsed(collapsed){
        if(collapsed){
            sidebar.classList.add('collapsed','w-16');
            sidebar.classList.remove('w-64');
            menuTexts().forEach(el => el.classList.add('hidden'));
            if(sidebarTitle()) sidebarTitle().classList.add('hidden');

            document.querySelectorAll('#sidebar nav a').forEach(a=>{
                a.classList.add('justify-center');
                a.classList.remove('px-3');
                a.classList.add('px-2');
            });

            getNavIcons().forEach(svg=>{
                svg.classList.remove('ml-auto');
                svg.classList.add('mx-auto','w-6','h-6');
            });

            if(iconLeft) iconLeft.classList.add('hidden');
            if(iconRight) iconRight.classList.remove('hidden');
        } else {
            sidebar.classList.remove('collapsed','w-16');
            sidebar.classList.add('w-64');
            menuTexts().forEach(el => el.classList.remove('hidden'));
            if(sidebarTitle()) sidebarTitle().classList.remove('hidden');

            document.querySelectorAll('#sidebar nav a').forEach(a=>{
                a.classList.remove('justify-center');
                a.classList.remove('px-2');
                a.classList.add('px-3');
            });

            getNavIcons().forEach(svg=>{
                svg.classList.remove('mx-auto','w-6','h-6');
                svg.classList.add('ml-auto','w-5','h-5');
            });

            if(iconLeft) iconLeft.classList.remove('hidden');
            if(iconRight) iconRight.classList.add('hidden');
        }
        localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');
    }

    const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    applyCollapsed(collapsed);

    if(btn){
        btn.addEventListener('click', ()=>{
            applyCollapsed(!sidebar.classList.contains('collapsed'));
        });
    }

    // re-create lucide icons after any DOM changes (safe to call)
    document.addEventListener('DOMContentLoaded', function(){ if(window.lucide && typeof lucide.createIcons === 'function') lucide.createIcons(); });
})();
</script>

<!-- CSS tweaks -->
<style>
#sidebar { overflow: visible; }
.nav-icon { transition: transform .15s ease; stroke-width: 1.6; }
#sidebar nav a:hover .nav-icon { transform: scale(1.05); }
#sidebar.w-16 { width: 4rem; } /* fallback */
#sidebar.w-64 { width: 16rem; }
#sidebar.collapsed nav a { text-align: center; }
</style>

</body>
</html>
