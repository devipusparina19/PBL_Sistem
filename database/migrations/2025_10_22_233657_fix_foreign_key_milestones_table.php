<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('milestones', function (Blueprint $table) {
            // Pastikan tidak ada foreign key lama yang masih nyangkut
            DB::statement('ALTER TABLE milestones DROP FOREIGN KEY IF EXISTS milestones_id_kelompok_foreign');
            DB::statement('ALTER TABLE milestones DROP FOREIGN KEY IF EXISTS milestones_kelompok_id_foreign');
        });

        Schema::table('milestones', function (Blueprint $table) {
            if (!Schema::hasColumn('milestones', 'kelompok_id') && Schema::hasColumn('milestones', 'id_kelompok')) {
                $table->renameColumn('id_kelompok', 'kelompok_id');
            }

            // Tambahkan foreign key baru kalau belum ada
            $table->foreign('kelompok_id')
                ->references('id_kelompok')
                ->on('kelompok')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('milestones', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->renameColumn('kelompok_id', 'id_kelompok');
        });
    }
};
