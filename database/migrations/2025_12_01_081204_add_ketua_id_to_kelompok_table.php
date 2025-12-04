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
        Schema::table('kelompok', function (Blueprint $table) {
            // Tambahkan kolom ketua_id
            $table->unsignedBigInteger('ketua_id')->nullable()->after('judul_proyek');

            // Foreign key mengarah ke tabel mahasiswas
            $table->foreign('ketua_id')
                  ->references('id')
                  ->on('mahasiswas')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            // Hapus foreign key & kolomnya
            $table->dropForeign(['ketua_id']);
            $table->dropColumn('ketua_id');
        });
    }
};
