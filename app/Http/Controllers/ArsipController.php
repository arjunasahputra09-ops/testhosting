<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    /* ===============================
     | INDEX (SEMUA USER)
     =============================== */
    public function index(Request $request)
    {
        $q = $request->get('q', null);

        // Jika ada query pencarian, lakukan pencarian dan kembalikan view pencarian
        if ($q !== null && $q !== '') {
            // Hanya user yang boleh melakukan pencarian
            abort_if(! Auth::check() || Auth::user()->role !== 'user', 403);

            $arsips = Arsip::where(function ($query) use ($q) {
                $query->where('kode_klasifikasi', 'like', "%{$q}%")
                      ->orWhere('jenis_arsip', 'like', "%{$q}%")
                      ->orWhere('uraian_masalah', 'like', "%{$q}%")
                      ->orWhere('kurun_waktu', 'like', "%{$q}%");
            })->latest()->paginate(10)->appends(['q' => $q]);

            return view('arsip.search', compact('arsips', 'q'));
        }

        // Jika admin, lihat semua arsip
        if (Auth::check() && Auth::user()->role === 'admin') {
            $arsips = Arsip::latest()->paginate(10);
            return view('arsip.index', compact('arsips'));
        }

        // Untuk guest atau user biasa: tampilkan hanya arsip yang sudah "approved"
        $arsips = Arsip::where('status', 'approved')->latest()->paginate(10);

        // Jika user terautentikasi, juga kirimkan arsip milik mereka (pending + lainnya)
        if (Auth::check() && Auth::user()->role === 'user') {
            $myArsips = Arsip::where('user_id', Auth::id())->latest()->paginate(10);
            return view('arsip.index', compact('arsips', 'myArsips'));
        }

        return view('arsip.index', compact('arsips'));
    }

    /* ===============================
     | SEARCH (USER SAJA)
     =============================== */
   public function search(Request $request)
    {
        // Pastikan terautentikasi (izinkan admin juga)
        abort_unless(Auth::check(), 403);

        $q = $request->get('q', '');

        $arsips = Arsip::where(function ($query) use ($q) {
            $query->where('kode_klasifikasi', 'like', "%{$q}%")
                  ->orWhere('jenis_arsip', 'like', "%{$q}%")
                  ->orWhere('uraian_masalah', 'like', "%{$q}%")
                  ->orWhere('pencipta_arsip', 'like', "%{$q}%")
                  ->orWhere('kurun_waktu', 'like', "%{$q}%");
        })->latest()->paginate(10)->appends(['q' => $q]);

        return view('arsip.search', compact('arsips', 'q'));
    }

    /* ===============================
     | CREATE (USER SAJA)
     =============================== */
    public function create()
    {
        abort_if(Auth::user()->role !== 'user', 403);
        return view('arsip.create');
    }

    /* ===============================
     | STORE (USER SAJA)
     =============================== */
    public function store(Request $request)
    {
        abort_if(! Auth::check() || Auth::user()->role !== 'user', 403);

        $data = $request->validate([
            'kode_klasifikasi' => 'required',
            'jenis_arsip' => 'required',
            'uraian_masalah' => 'required',
            'kurun_waktu' => 'required',
            'file' => 'nullable|file|max:102400',
            'no_urut' => 'nullable|integer',
            'pencipta_arsip' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('arsip_files', 'public');
        } else {
            $filePath = null;
        }

        // generate no_urut jika tidak diisi
        $max = Arsip::max('no_urut');
        $generatedNo = $max ? $max + 1 : 1;
        $noUrut = $request->input('no_urut') ?? $generatedNo;

        // assign manual ke model (menghindari masalah fillable)
        $arsip = new Arsip();
        $arsip->kode_klasifikasi = $data['kode_klasifikasi'];
        $arsip->jenis_arsip = $data['jenis_arsip'];
        $arsip->uraian_masalah = $data['uraian_masalah'];
        $arsip->kurun_waktu = $data['kurun_waktu'];
        $arsip->file = $filePath;
        $arsip->status = 'pending';
        $arsip->user_id = Auth::id();
        $arsip->no_urut = $noUrut;
        $arsip->pencipta_arsip = $data['pencipta_arsip'] ?? null;
        $arsip->save();

        return redirect()->route('arsip.index')
            ->with('success', 'Arsip berhasil ditambahkan');
    }

    /* ===============================
     | EDIT (USER SAJA)
     =============================== */
    public function edit(Arsip $arsip)
    {
        // Hanya pemilik (user) boleh edit
        abort_unless(Auth::check() && Auth::user()->role === 'user' && $arsip->user_id === Auth::id(), 403);
        return view('arsip.edit', compact('arsip'));
    }

    /* ===============================
     | UPDATE (USER SAJA)
     =============================== */
    public function update(Request $request, Arsip $arsip)
    {
        // Hanya pemilik (user) boleh update
        abort_unless(Auth::check() && Auth::user()->role === 'user' && $arsip->user_id === Auth::id(), 403);

        $data = $request->validate([
            'kode_klasifikasi' => 'required',
            'jenis_arsip' => 'required',
            'uraian_masalah' => 'required',
            'kurun_waktu' => 'required',
            'file' => 'nullable|file|max:102400',
            'no_urut' => 'nullable|integer',
            'pencipta_arsip' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('file')) {
            if ($arsip->file) {
                Storage::disk('public')->delete($arsip->file);
            }
            $arsip->file = $request->file('file')->store('arsip_files', 'public');
        }

        // update fields manually
        $arsip->kode_klasifikasi = $data['kode_klasifikasi'];
        $arsip->jenis_arsip = $data['jenis_arsip'];
        $arsip->uraian_masalah = $data['uraian_masalah'];
        $arsip->kurun_waktu = $data['kurun_waktu'];
        $arsip->no_urut = $data['no_urut'] ?? $arsip->no_urut;
        $arsip->pencipta_arsip = $data['pencipta_arsip'] ?? $arsip->pencipta_arsip;

        $arsip->status = 'pending';

        $arsip->save();

        return redirect()->route('arsip.index')
            ->with('success', 'Arsip diperbarui');
    }

    /* ===============================
     | DELETE (USER SAJA)
     =============================== */
    public function destroy(Arsip $arsip)
    {
        // Hanya pemilik (user) boleh menghapus
        abort_unless(Auth::check() && Auth::user()->role === 'user' && $arsip->user_id === Auth::id(), 403);

        if ($arsip->file && Storage::disk('public')->exists($arsip->file)) {
            Storage::disk('public')->delete($arsip->file);
        }

        $arsip->delete();

        return back()->with('success', 'Arsip dihapus');
    }

    /* ===============================
     | IMPORT (USER SAJA)
     =============================== */
    public function showImportForm()
    {
        abort_if(Auth::user()->role !== 'user', 403);
        return view('arsip.import');
    }

    /* ===============================
     | VERIFIKASI (ADMIN SAJA)
     =============================== */
    public function verifikasiIndex()
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $arsips = Arsip::where('status', 'pending')->paginate(10);
        return view('arsip.verifikasi', compact('arsips'));
    }

    public function verifikasiApprove(Arsip $arsip)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $arsip->update(['status' => 'approved']);

        return back()->with('success', 'Arsip diverifikasi');
    }

    public function handleImport(Request $request)
    {
        abort_if(Auth::user()->role !== 'user', 403);

        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx|max:20480',
        ]);

        $file = $request->file('import_file');
        $ext = strtolower($file->getClientOriginalExtension());

        // only process CSV here
        if ($ext !== 'csv') {
            return redirect()->route('arsip.index')
                ->with('success', 'Hanya file CSV yang saat ini didukung. Silakan convert XLSX ke CSV lalu ulangi.');
        }

        $stored = $file->store('imports', 'public');
        $path = storage_path('app/public/' . $stored);

        if (!file_exists($path)) {
            return redirect()->route('arsip.import.form')
                ->with('success', 'File gagal disimpan untuk proses import.');
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return redirect()->route('arsip.import.form')
                ->with('success', 'Gagal membuka file CSV.');
        }

        $rowNum = 0;
        $imported = 0;
        $skipped = 0;

        // read header
        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return redirect()->route('arsip.import.form')
                ->with('success', 'File CSV kosong atau format tidak valid.');
        }

        // normalize header names
        $map = [];
        foreach ($header as $i => $h) {
            $key = strtolower(trim(str_replace(['"',"'"], '', $h)));
            $map[$key] = $i;
        }

        // expected header keys (flexible)
        $expected = [
            'no', 'no_urut',
            'pencipta arsip', 'pencipta_arsip', 'pencipta',
            'kode klasifikasi', 'kode_klasifikasi', 'kode',
            'jenis arsip', 'jenis_arsip', 'jenis',
            'uraian masalah', 'uraian_masalah', 'uraian',
            'kurun waktu', 'kurun_waktu', 'kurun',
            'tingkat perkembangan', 'tingkat_perkembangan',
            'jumlah berkas', 'jumlah_berkas',
            'no boks', 'no_boks',
            'keterangan',
        ];

        // helper to find index by a list of possible names
        $findIndex = function(array $names) use ($map) {
            foreach ($names as $n) {
                $n = strtolower(trim($n));
                if (isset($map[$n])) return $map[$n];
            }
            return null;
        };

        // prepare indices
        $idx_no = $findIndex(['no','no_urut']);
        $idx_pencipta = $findIndex(['pencipta arsip','pencipta_arsip','pencipta']);
        $idx_kode = $findIndex(['kode klasifikasi','kode_klasifikasi','kode']);
        $idx_jenis = $findIndex(['jenis arsip','jenis_arsip','jenis']);
        $idx_uraian = $findIndex(['uraian masalah','uraian_masalah','uraian']);
        $idx_kurun = $findIndex(['kurun waktu','kurun_waktu','kurun']);
        $idx_tingkat = $findIndex(['tingkat perkembangan','tingkat_perkembangan']);
        $idx_jumlah = $findIndex(['jumlah berkas','jumlah_berkas']);
        $idx_boks = $findIndex(['no boks','no_boks']);
        $idx_ket = $findIndex(['keterangan']);

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            // skip completely empty rows
            $allEmpty = true;
            foreach ($row as $c) { if (trim($c) !== '') { $allEmpty = false; break; } }
            if ($allEmpty) { $skipped++; continue; }

            // basic required fields: kode or uraian or jenis
            $kode = isset($idx_kode) && isset($row[$idx_kode]) ? trim($row[$idx_kode]) : null;
            $jenis = isset($idx_jenis) && isset($row[$idx_jenis]) ? trim($row[$idx_jenis]) : null;
            $uraian = isset($idx_uraian) && isset($row[$idx_uraian]) ? trim($row[$idx_uraian]) : null;

            if (empty($kode) && empty($uraian) && empty($jenis)) {
                $skipped++;
                continue;
            }

            // create Arsip manually
            $arsip = new Arsip();
            $arsip->no_urut = (isset($idx_no) && isset($row[$idx_no]) && trim($row[$idx_no]) !== '') ? trim($row[$idx_no]) : null;
            $arsip->pencipta_arsip = (isset($idx_pencipta) && isset($row[$idx_pencipta])) ? trim($row[$idx_pencipta]) : null;
            $arsip->kode_klasifikasi = $kode ?? null;
            $arsip->jenis_arsip = $jenis ?? null;
            $arsip->uraian_masalah = $uraian ?? null;
            $arsip->kurun_waktu = (isset($idx_kurun) && isset($row[$idx_kurun])) ? trim($row[$idx_kurun]) : null;
            // optional fields (store in columns if exist on model)
            if (isset($idx_tingkat) && isset($row[$idx_tingkat])) $arsip->tingkat_perkembangan = trim($row[$idx_tingkat]);
            if (isset($idx_jumlah) && isset($row[$idx_jumlah])) $arsip->jumlah_berkas = trim($row[$idx_jumlah]);
            if (isset($idx_boks) && isset($row[$idx_boks])) $arsip->no_boks = trim($row[$idx_boks]);
            if (isset($idx_ket) && isset($row[$idx_ket])) $arsip->keterangan = trim($row[$idx_ket]);

            $arsip->file = null;
            $arsip->status = 'pending';
            $arsip->user_id = Auth::id();

            try {
                $arsip->save();
                $imported++;
            } catch (\Throwable $e) {
                // if save fails, skip and continue
                $skipped++;
                continue;
            }
        }

        fclose($handle);

        return redirect()->route('arsip.index')
            ->with('success', "Import selesai. Berhasil: {$imported}, Dilewati: {$skipped}.");
    }

    /* ===============================
     | DOWNLOAD (SEMUA USER DENGAN KONDISI)
     =============================== */
    public function download(Arsip $arsip)
    {
        abort_unless(Auth::check(), 403);

        // Jika arsip belum approved, hanya admin atau pemilik yang boleh mendownload
        if ($arsip->status !== 'approved' && ! (Auth::user()->role === 'admin' || $arsip->user_id === Auth::id())) {
            abort(403);
        }

        if (! $arsip->file || ! Storage::disk('public')->exists($arsip->file)) {
            abort(404);
        }

        return Storage::disk('public')->download($arsip->file, basename($arsip->file));
    }

   public function exportCsv(Request $request)
{
    abort_unless(Auth::check(), 403);

    $user = Auth::user();
    $q = $request->get('q');

    $query = Arsip::query();

    if ($user->role === 'user') {
        // admin: semua arsip
    } else {
        // user: approved + milik sendiri
        $query->where(function ($q) use ($user) {
            $q->where('status', 'approved')
              ->orWhere('user_id', $user->id);
        });
    }

    if ($q) {
        $query->where(function ($builder) use ($q) {
            $builder->where('kode_klasifikasi', 'like', "%{$q}%")
                    ->orWhere('uraian_masalah', 'like', "%{$q}%")
                    ->orWhere('pencipta_arsip', 'like', "%{$q}%");
        });
    }

    $arsips = $query->orderBy('id')->get();

        $filename = 'arsip_export_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($arsips) {
            $handle = fopen('php://output', 'w');
            // header kolom CSV
            fputcsv($handle, ['No', 'Kode Klasifikasi', 'Jenis Arsip', 'Uraian Masalah', 'Pencipta', 'Kurun Waktu','Tingkat Perkembangan','Jumlah Arsip', 'No Boks', 'Keterangan',]);
            foreach ($arsips as $index => $a) {
                fputcsv($handle, [
                    $index + 1,
                    $a->kode_klasifikasi,
                    $a->jenis_arsip,
                    $a->uraian_masalah,
                    $a->pencipta_arsip,
                    $a->kurun_waktu,
                    $a->tingkat_perkembangan,
                    $a->jumlah_berkas,
                    $a->no_boks,
                    $a->keterangan,
                    
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}