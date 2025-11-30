<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->integer('nilai_total')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->dropColumn('nilai_total');
        });
    }
};
