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
        Schema::table('nilai', function (Blueprint $table) {
            $table->decimal('uts', 5, 2)->nullable()->after('kontribusi')->comment('UTS untuk Pengambilan Keputusan (10%)');
            $table->decimal('uas', 5, 2)->nullable()->after('uts')->comment('UAS untuk Pengambilan Keputusan (10%)');
            $table->decimal('hasil_proyek', 5, 2)->nullable()->after('uas')->comment('Komponen Hasil Proyek untuk Pengambilan Keputusan (30%)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn(['uts', 'uas', 'hasil_proyek']);
        });
    }
};
