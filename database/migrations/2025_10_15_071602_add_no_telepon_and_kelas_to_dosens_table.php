<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (!Schema::hasColumn('dosens', 'no_telp')) {
                $table->string('no_telp', 20)->nullable()->after('email');
            }

            if (!Schema::hasColumn('dosens', 'kelas')) {
                $table->string('kelas', 50)->nullable()->after('no_telp');
            }
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn(['no_telp', 'kelas']);
        });
    }
};
