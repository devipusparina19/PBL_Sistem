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
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu kalau belum ada kolom role, baru tambahkan
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', [
                    'admin',
                    'dosen',
                    'mahasiswa',
                    'koordinator_pbl',
                    'koordinator_prodi'
                ])->default('mahasiswa')->after('password');
            }
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
