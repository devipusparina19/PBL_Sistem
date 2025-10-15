<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan tabel.
     */
    public function up(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            // Hapus kolom 'status'
            $table->dropColumn('status');
        });
    }

    /**
     * Kembalikan perubahan (rollback)
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            // Tambahkan kembali kolom 'status' jika rollback
            $table->string('status')->nullable();
        });
    }
};
