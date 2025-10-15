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
        Schema::table('kelompoks', function (Blueprint $table) {
            // Nilai kelompok (6 komponen)
            $table->decimal('pemrograman_web', 5, 2)->nullable()->after('judul_proyek');
            $table->decimal('integrasi_sistem', 5, 2)->nullable()->after('pemrograman_web');
            $table->decimal('pengambilan_keputusan', 5, 2)->nullable()->after('integrasi_sistem');
            $table->decimal('it_proyek', 5, 2)->nullable()->after('pengambilan_keputusan');
            $table->decimal('kontribusi_kelompok', 5, 2)->nullable()->after('it_proyek');
            $table->decimal('penilaian_dosen', 5, 2)->nullable()->after('kontribusi_kelompok');
            $table->decimal('hasil_akhir', 5, 2)->nullable()->after('penilaian_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompoks', function (Blueprint $table) {
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
