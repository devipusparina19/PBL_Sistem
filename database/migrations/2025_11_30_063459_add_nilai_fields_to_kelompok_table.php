<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->integer('pemrograman_web')->nullable();
            $table->integer('integrasi_sistem')->nullable();
            $table->integer('pengambilan_keputusan')->nullable();
            $table->integer('it_proyek')->nullable();
            $table->integer('kontribusi_kelompok')->nullable();
            $table->integer('penilaian_dosen')->nullable();
            $table->float('hasil_akhir')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropColumn([
                'pemrograman_web',
                'integrasi_sistem',
                'pengambilan_keputusan',
                'it_proyek',
                'kontribusi_kelompok',
                'penilaian_dosen',
                'hasil_akhir',
            ]);
        });
    }
};
