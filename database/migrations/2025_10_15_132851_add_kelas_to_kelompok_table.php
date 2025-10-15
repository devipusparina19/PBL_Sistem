<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            // Menambahkan kolom kelas ke tabel kelompok
            $table->string('kelas')->nullable()->after('judul_proyek');
        });
    }

    /**
     * Balikkan migration.
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropColumn('kelas');
        });
    }
};
