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
        Schema::table('users', function (Blueprint $table) {
            // tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('users', 'nim_nip')) {
                $table->string('nim_nip')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'kelas')) {
                $table->string('kelas')->nullable()->after('nim_nip');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('kelas');
            }

            if (!Schema::hasColumn('users', 'role_kelompok')) {
                $table->string('role_kelompok')->nullable()->after('role');
            }

            if (!Schema::hasColumn('users', 'role_di_kelompok')) {
                $table->string('role_di_kelompok')->nullable()->after('role_kelompok');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim_nip', 'kelas', 'role', 'role_kelompok', 'role_di_kelompok']);
        });
    }
};
