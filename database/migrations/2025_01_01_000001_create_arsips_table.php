<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->string('no_urut')->nullable();
            $table->string('pencipta_arsip')->nullable();
            $table->string('kode_klasifikasi');
            $table->string('jenis_arsip');
            $table->text('uraian_masalah');
            $table->string('kurun_waktu');
            $table->enum('tingkat_perkembangan', ['Asli', 'Salinan'])->nullable();
            $table->string('jumlah_berkas')->nullable();
            $table->string('no_boks')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
