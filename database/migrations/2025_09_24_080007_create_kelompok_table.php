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
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id('id_kelompok'); // primary key
            $table->string('kode_mk');
            $table->string('nama_kelompok');
            $table->text('deskripsi')->nullable();
            $table->string('judul_proyek');
            $table->string('nip'); // nanti bisa dijadikan foreign key
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok');
    }
};