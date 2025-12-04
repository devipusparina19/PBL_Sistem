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
            
            if (!Schema::hasColumn('nilai', 'it_proposal')) {
                $table->decimal('it_proposal', 5, 2)->nullable()->after('catatan');
            }
            
            if (!Schema::hasColumn('nilai', 'it_progress_report')) {
                $table->decimal('it_progress_report', 5, 2)->nullable()->after('it_proposal');
            }
            
            if (!Schema::hasColumn('nilai', 'it_final_project')) {
                $table->decimal('it_final_project', 5, 2)->nullable()->after('it_progress_report');
            }
            
            if (!Schema::hasColumn('nilai', 'it_presentasi')) {
                $table->decimal('it_presentasi', 5, 2)->nullable()->after('it_final_project');
            }
            
            if (!Schema::hasColumn('nilai', 'it_dokumentasi')) {
                $table->decimal('it_dokumentasi', 5, 2)->nullable()->after('it_presentasi');
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
            if (Schema::hasColumn('nilai', 'it_proposal')) {
                $table->dropColumn('it_proposal');
            }
            
            if (Schema::hasColumn('nilai', 'it_progress_report')) {
                $table->dropColumn('it_progress_report');
            }
            
            if (Schema::hasColumn('nilai', 'it_final_project')) {
                $table->dropColumn('it_final_project');
            }
            
            if (Schema::hasColumn('nilai', 'it_presentasi')) {
                $table->dropColumn('it_presentasi');
            }
            
            if (Schema::hasColumn('nilai', 'it_dokumentasi')) {
                $table->dropColumn('it_dokumentasi');
            }
        });
    }
};
