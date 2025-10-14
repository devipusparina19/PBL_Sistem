<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('role_kelompok')->default(0); // default 0
            $table->string('role_di_kelompok')->default('tidak ada'); // default
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_kelompok');
            $table->dropColumn('role_di_kelompok');
        });
    }
};
