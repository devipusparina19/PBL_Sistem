<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
    $table->id();
    $table->string('nim')->unique();
    $table->string('nama');
    $table->string('kelas');
    $table->string('angkatan');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('foto')->nullable();

    $table->unsignedBigInteger('kelompok_id')->nullable();
    $table->foreign('kelompok_id')
          ->references('id_kelompok')
          ->on('kelompok')
          ->onDelete('set null');

    $table->timestamps();
});
    }
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
