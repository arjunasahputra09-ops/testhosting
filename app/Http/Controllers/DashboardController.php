<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect dashboard berdasarkan role
     */
    public function index()
    {
        return Auth::user()->role === 'admin'
            ? redirect()->route('dashboard.admin')
            : redirect()->route('dashboard.user');
    }

    /**
     * ===================== DASHBOARD ADMIN =====================
     */
    public function admin()
    {
        $totalUser = User::count();

        $arsipMenunggu  = Arsip::where('status', 'pending')->count();
        $arsipDisetujui = Arsip::where('status', 'approved')->count();

        return view('dashboard.admin', compact(
            'totalUser',
            'arsipMenunggu',
            'arsipDisetujui'
        ));
    }

    /**
     * ===================== DASHBOARD USER =====================
     */
    public function user()
{
    // =====================
    // STATISTIK UTAMA
    // =====================
    $totalArsip   = Arsip::count();
    $arsipDigital = Arsip::whereNotNull('file')->count();
    $arsipFisik   = Arsip::whereNull('file')->count();

    // Arsip tahun ini
    $arsipTahunIni = Arsip::whereYear('created_at', now()->year)->count();

    // =====================
    // ARSIP TERBARU
    // =====================
    $arsipTerbaru = Arsip::select(
            'uraian_masalah',
            'kode_klasifikasi',
            'pencipta_arsip',
            'created_at'
        )
        ->latest()
        ->limit(5)
        ->get();

    // =====================
    // GRAFIK JENIS ARSIP
    // =====================
    $grafikJenis = Arsip::selectRaw('jenis_arsip, COUNT(*) as total')
        ->groupBy('jenis_arsip')
        ->pluck('total', 'jenis_arsip');

    // =====================
    // GRAFIK MEDIA ARSIP
    // =====================
    $grafikMedia = collect([
        'Digital' => Arsip::whereNotNull('file')->count(),
        'Fisik'   => Arsip::whereNull('file')->count(),
    ]);

    return view('dashboard.user', compact(
        'totalArsip',
        'arsipDigital',
        'arsipFisik',
        'arsipTahunIni',
        'arsipTerbaru',
        'grafikJenis',
        'grafikMedia'
    ));
}

}
