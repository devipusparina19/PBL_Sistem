<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_kuliah', 'nip_dosen')) {
                $table->string('nip_dosen', 30)->nullable()->after('nama_mk');
            }

            if (!Schema::hasColumn('mata_kuliah', 'kelas')) {
                $table->string('kelas', 10)->nullable()->after('nip_dosen');
            }

            if (!Schema::hasColumn('mata_kuliah', 'semester')) {
                $table->string('semester', 10)->nullable()->after('kelas');
            }
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropColumn(['nip_dosen', 'kelas', 'semester']);
        });
    }
};
