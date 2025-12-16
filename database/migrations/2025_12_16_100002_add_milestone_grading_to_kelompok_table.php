<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->decimal('nilai_milestone_avg', 5, 2)->nullable()->after('hasil_akhir');
            $table->integer('milestone_approved_count')->default(0)->after('nilai_milestone_avg');
            $table->decimal('nilai_rata_anggota', 5, 2)->nullable()->after('milestone_approved_count');
        });
    }

    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropColumn(['nilai_milestone_avg', 'milestone_approved_count', 'nilai_rata_anggota']);
        });
    }
};
