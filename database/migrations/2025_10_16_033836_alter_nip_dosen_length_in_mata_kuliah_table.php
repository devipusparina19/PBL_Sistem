<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->string('nip_dosen', 100)->change(); // ✅ ubah panjang kolom
        });
    }

    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->string('nip_dosen', 30)->change();
        });
    }
};
