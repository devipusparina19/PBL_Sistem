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
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->enum('kelas', ['3A', '3B', '3C', '3D', '3E'])->after('nama_mk')->nullable()->comment('Kelas mata kuliah');
            $table->string('nip_dosen', 30)->after('kelas')->nullable()->comment('NIP Dosen pengampu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'nip_dosen']);
        });
    }
};
