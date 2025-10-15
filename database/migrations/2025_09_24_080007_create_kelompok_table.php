<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id('id_kelompok'); // Primary key auto-increment
            $table->string('kode_mk'); // contoh: A01K
            $table->string('nama_kelompok'); // contoh: 2
            $table->string('judul_proyek'); // contoh: Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL
            $table->string('kelas'); // contoh: 3E
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok');
    }
};
