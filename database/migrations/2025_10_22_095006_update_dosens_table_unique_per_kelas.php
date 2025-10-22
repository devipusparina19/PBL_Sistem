<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            // Hapus index unik global lama (kalau sudah ada)
            $table->dropUnique(['nip']);
            $table->dropUnique(['email']);
        });

        Schema::table('dosens', function (Blueprint $table) {
            // Tambah unique index gabungan (kelas + nip) dan (kelas + email)
            $table->unique(['kelas', 'nip']);
            $table->unique(['kelas', 'email']);
        });
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            // Rollback ke aturan lama (nip dan email unik secara global)
            $table->dropUnique(['kelas', 'nip']);
            $table->dropUnique(['kelas', 'email']);
            $table->unique('nip');
            $table->unique('email');
        });
    }
};

