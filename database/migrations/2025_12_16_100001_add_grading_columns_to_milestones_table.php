<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('milestones', function (Blueprint $table) {
            $table->decimal('nilai', 5, 2)->nullable()->after('catatan_dosen');
            $table->unsignedTinyInteger('target_minggu')->nullable()->after('nilai');
            $table->decimal('nilai_akhir', 5, 2)->nullable()->after('target_minggu');
        });
    }

    public function down(): void
    {
        Schema::table('milestones', function (Blueprint $table) {
            $table->dropColumn(['nilai', 'target_minggu', 'nilai_akhir']);
        });
    }
};
