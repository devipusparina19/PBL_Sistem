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
            // Kolom untuk PWL (Pemrograman Web Lanjut)
            $table->decimal('pwl_tugas_quiz', 5, 2)->nullable()->after('catatan');
            $table->decimal('pwl_project', 5, 2)->nullable()->after('pwl_tugas_quiz');
            $table->decimal('pwl_presentasi', 5, 2)->nullable()->after('pwl_project');
            
            // Kolom untuk IT Project
            $table->decimal('it_proposal', 5, 2)->nullable()->after('pwl_presentasi');
            $table->decimal('it_progress_report', 5, 2)->nullable()->after('it_proposal');
            $table->decimal('it_final_project', 5, 2)->nullable()->after('it_progress_report');
            $table->decimal('it_presentasi', 5, 2)->nullable()->after('it_final_project');
            $table->decimal('it_dokumentasi', 5, 2)->nullable()->after('it_presentasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn([
                'pwl_tugas_quiz',
                'pwl_project',
                'pwl_presentasi',
                'it_proposal',
                'it_progress_report',
                'it_final_project',
                'it_presentasi',
                'it_dokumentasi'
            ]);
        });
    }
};
