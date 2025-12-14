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
            $table->double('pemrograman_web')->nullable()->default(0);
            $table->double('integrasi_sistem')->nullable()->default(0);
            $table->double('pengambilan_keputusan')->nullable()->default(0);
            $table->double('it_proyek')->nullable()->default(0);
            $table->double('kontribusi_kelompok')->nullable()->default(0);
            $table->double('penilaian_dosen')->nullable()->default(0);
            $table->double('hasil_akhir')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
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
                'hasil_akhir'
            ]);
        });
    }
};
