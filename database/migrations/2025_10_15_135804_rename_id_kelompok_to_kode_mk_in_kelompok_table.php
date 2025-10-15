<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->renameColumn('id_kelompok', 'kode_mk');
        });
    }

    public function down(): void
    {
        Schema::table('kelompok', function (Blueprint $table) {
            $table->renameColumn('kode_mk', 'id_kelompok');
        });
    }
};
