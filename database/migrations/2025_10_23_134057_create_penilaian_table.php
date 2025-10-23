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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id('id_penilaian');
            $table->unsignedBigInteger('kelompok_id');
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->integer('nilai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Relasi ke tabel kelompok
            $table->foreign('kelompok_id')
                  ->references('id_kelompok')
                  ->on('kelompok')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
