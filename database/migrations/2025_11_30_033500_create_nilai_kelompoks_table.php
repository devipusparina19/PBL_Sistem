<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilai_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelompok_id');
            $table->integer('presentasi');
            $table->integer('laporan');
            $table->integer('kerjasama');
            $table->integer('nilai_akhir');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('nilai_kelompoks');
    }
};
