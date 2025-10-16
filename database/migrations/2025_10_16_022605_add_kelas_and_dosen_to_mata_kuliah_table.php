<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            // Tambah kolom kelas hanya jika belum ada
            if (!Schema::hasColumn('mata_kuliah', 'kelas')) {
                $table->enum('kelas', ['3A', '3B', '3C', '3D', '3E'])
                      ->after('nama_mk')
                      ->nullable()
                      ->comment('Kelas mata kuliah');
            }

            // Tambah kolom nip_dosen hanya jika belum ada
            if (!Schema::hasColumn('mata_kuliah', 'nip_dosen')) {
                $table->string('nip_dosen', 30)
                      ->after('kelas')
                      ->nullable()
                      ->comment('NIP Dosen pengampu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            if (Schema::hasColumn('mata_kuliah', 'kelas')) {
                $table->dropColumn('kelas');
            }
            if (Schema::hasColumn('mata_kuliah', 'nip_dosen')) {
                $table->dropColumn('nip_dosen');
            }
        });
    }
};
