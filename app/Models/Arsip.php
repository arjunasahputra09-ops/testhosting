<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     */
    protected $fillable = [
        'no_urut',
        'pencipta_arsip',
        'kode_klasifikasi',
        'jenis_arsip',
        'uraian_masalah',
        'kurun_waktu',
        'tingkat_perkembangan',
        'jumlah_berkas',
        'no_boks',
        'keterangan',
        'file',
        'status',
        'user_id', 
    ];

    /**
     * Atribut yang di-cast otomatis (opsional).
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke user (pemilik arsip)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
