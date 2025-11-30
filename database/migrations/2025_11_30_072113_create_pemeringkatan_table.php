<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeringkatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelompok');
            $table->integer('nilai_total')->default(0);
            $table->integer('peringkat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeringkatan');
    }
};
