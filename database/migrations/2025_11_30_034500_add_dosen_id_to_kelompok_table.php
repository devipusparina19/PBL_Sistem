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
        Schema::table('kelompok', function (Blueprint $table) {
            // Tambah kolom dosen_id jika belum ada
            if (!Schema::hasColumn('kelompok', 'dosen_id')) {
                $table->unsignedBigInteger('dosen_id')->nullable()->after('nama_kelompok');

                // Foreign key ke tabel dosen
                $table->foreign('dosen_id')
                      ->references('id')
                      ->on('dosen')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Undo perubahan (rollback).
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            if (Schema::hasColumn('kelompok', 'dosen_id')) {
                $table->dropForeign(['dosen_id']);
                $table->dropColumn('dosen_id');
            }
        });
    }
};
