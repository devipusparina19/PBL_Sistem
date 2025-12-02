<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->unsignedTinyInteger('minggu_ke');

            // relasi ke users
            $table->unsignedBigInteger('user_id');

            // relasi ke tabel kelompok
            $table->unsignedBigInteger('kelompok_id');

            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])
                ->default('menunggu');

            $table->text('catatan_dosen')->nullable();

            $table->timestamps();

            // FK ke users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // FK ke kelompok (INI YANG BENAR)
            $table->foreign('kelompok_id')
                ->references('id_kelompok')
                ->on('kelompok')     // <-- PERBAIKI BAGIAN INI
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
