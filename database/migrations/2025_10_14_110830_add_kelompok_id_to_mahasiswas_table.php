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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->unsignedBigInteger('kelompok_id')->nullable()->after('angkatan');
            $table->foreign('kelompok_id')
                  ->references('id_kelompok')
                  ->on('kelompoks')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn('kelompok_id');
        });
    }
};
