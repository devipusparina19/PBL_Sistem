<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        schema::table('nilai', function (Blueprint $table) {
            $table->decimal('nilai_kerja', 5, 2)->nullable()->after('hasil_proyek');
            $table->decimal('nilai_laporan', 5, 2)->nullable()->after('nilai_kerja');
            $table->decimal('ujian_praktikum_1', 5, 2)->nullable()->after('nilai_laporan');
            $table->decimal('ujian_praktikum_2', 5, 2)->nullable()->after('ujian_praktikum_1');
        });
    }

    public function down()
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn(['nilai_kerja', 'nilai_laporan', 'ujian_praktikum_1', 'ujian_praktikum_2']);
        });
    }
};
