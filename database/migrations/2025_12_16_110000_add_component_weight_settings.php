<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // IT Project Component Weights
        DB::table('settings')->insert([
            ['key' => 'it_aktivitas_partisipatif', 'value' => '20', 'description' => 'IT Project: Aktivitas Partisipatif (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'it_presentasi', 'value' => '10', 'description' => 'IT Project: Presentasi (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'it_objektivitas', 'value' => '10', 'description' => 'IT Project: Objektivitas/Tanya Jawab (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'it_laporan_progres', 'value' => '10', 'description' => 'IT Project: Laporan Progres (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'it_laporan_akhir', 'value' => '10', 'description' => 'IT Project: Laporan Akhir (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'it_produk_aplikasi', 'value' => '40', 'description' => 'IT Project: Produk Aplikasi (%)', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // PWL Component Weights
        DB::table('settings')->insert([
            ['key' => 'pwl_proposal', 'value' => '15', 'description' => 'PWL: Proposal (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'pwl_progress_report', 'value' => '15', 'description' => 'PWL: Progress Report (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'pwl_final_project', 'value' => '40', 'description' => 'PWL: Final Project (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'pwl_presentasi', 'value' => '20', 'description' => 'PWL: Presentasi (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'pwl_dokumentasi', 'value' => '10', 'description' => 'PWL: Dokumentasi (%)', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Integrasi Sistem Component Weights
        DB::table('settings')->insert([
            ['key' => 'integrasi_nilai_kerja', 'value' => '27', 'description' => 'Integrasi: Nilai Kerja (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'integrasi_nilai_laporan', 'value' => '18', 'description' => 'Integrasi: Nilai Laporan (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'integrasi_ujian_praktikum_1', 'value' => '12.5', 'description' => 'Integrasi: Ujian Praktikum 1 (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'integrasi_ujian_praktikum_2', 'value' => '12.5', 'description' => 'Integrasi: Ujian Praktikum 2 (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'integrasi_uts', 'value' => '15', 'description' => 'Integrasi: UTS (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'integrasi_uas', 'value' => '15', 'description' => 'Integrasi: UAS (%)', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Pengambilan Keputusan Component Weights
        DB::table('settings')->insert([
            ['key' => 'tpk_uts', 'value' => '10', 'description' => 'TPK: UTS (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'tpk_uas', 'value' => '10', 'description' => 'TPK: UAS (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'tpk_aktivitas_partisipatif', 'value' => '10', 'description' => 'TPK: Keaktifan (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'tpk_nilai_kerja', 'value' => '20', 'description' => 'TPK: Nilai Kerja (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'tpk_penyajian_dokumentasi', 'value' => '20', 'description' => 'TPK: Penyajian & Dokumentasi (%)', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'tpk_hasil_proyek', 'value' => '30', 'description' => 'TPK: Hasil Proyek (%)', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        // Delete component weight settings
        DB::table('settings')->where('key', 'like', 'it_%')->delete();
        DB::table('settings')->where('key', 'like', 'pwl_%')->delete();
        DB::table('settings')->where('key', 'like', 'integrasi_%')->delete();
        DB::table('settings')->where('key', 'like', 'tpk_%')->delete();
    }
};
