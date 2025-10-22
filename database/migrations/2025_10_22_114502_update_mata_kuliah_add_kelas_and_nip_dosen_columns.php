<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            // Tambah kolom baru kalau belum ada
            if (!Schema::hasColumn('mata_kuliah', 'kelas')) {
                $table->string('kelas')->default('3A');
            }
            if (!Schema::hasColumn('mata_kuliah', 'nip_dosen')) {
                $table->text('nip_dosen')->nullable();
            }

            // Hapus unique lama dan ganti dengan kombinasi
            $table->dropUnique('mata_kuliah_kode_mk_unique');
            $table->unique(['kode_mk', 'kelas']);
        });
    }

    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            // Hapus kombinasi unik
            $table->dropUnique(['kode_mk', 'kelas']);
            // Tambahkan kembali unique hanya di kode_mk
            $table->unique('kode_mk');
        });
    }
};
