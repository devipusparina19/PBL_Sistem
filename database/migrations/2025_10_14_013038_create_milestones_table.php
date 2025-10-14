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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kelompok_id'); // ID kelompok
            $table->enum('status', ['menunggu','disetujui','ditolak'])->default('menunggu');
            $table->text('catatan_dosen')->nullable();
            $table->timestamps();

            // Relasi foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kelompok_id')->references('id')->on('kelompoks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};