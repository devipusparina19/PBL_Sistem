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
        Schema::table('nilai', function (Blueprint $table) {
            // Cek apakah kolom belum ada sebelum menambahkan
            // Ini mencegah error "Duplicate column" jika migration dijalankan ulang
            
            if (!Schema::hasColumn('nilai', 'pwl_tugas_quiz')) {
                $table->decimal('pwl_tugas_quiz', 5, 2)->nullable()->after('catatan');
            }
            
            if (!Schema::hasColumn('nilai', 'pwl_project')) {
                $table->decimal('pwl_project', 5, 2)->nullable()->after('pwl_tugas_quiz');
            }
            
            if (!Schema::hasColumn('nilai', 'pwl_presentasi')) {
                $table->decimal('pwl_presentasi', 5, 2)->nullable()->after('pwl_project');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Hanya drop kolom jika ada
            if (Schema::hasColumn('nilai', 'pwl_tugas_quiz')) {
                $table->dropColumn('pwl_tugas_quiz');
            }
            
            if (Schema::hasColumn('nilai', 'pwl_project')) {
                $table->dropColumn('pwl_project');
            }
            
            if (Schema::hasColumn('nilai', 'pwl_presentasi')) {
                $table->dropColumn('pwl_presentasi');
            }
        });
    }
};
