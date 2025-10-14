<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_kelompok')) {
                $table->tinyInteger('role_kelompok')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'role_di_kelompok')) {
                $table->string('role_di_kelompok')->nullable()->after('role_kelompok');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role_kelompok', 'role_di_kelompok']);
        });
    }
};
