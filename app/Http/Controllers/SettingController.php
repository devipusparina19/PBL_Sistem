<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'bobot_milestone' => 'required|integer|min:0|max:100',
            'bobot_nilai_anggota' => 'required|integer|min:0|max:100',
            'minimum_milestone' => 'required|integer|min:1',
            'bonus_per_minggu' => 'required|integer|min:0|max:50',
            'penalty_per_minggu' => 'required|integer|min:0|max:50',
        ]);

        // Validate total weight = 100
        $totalBobot = $request->bobot_milestone + $request->bobot_nilai_anggota;
        if ($totalBobot != 100) {
            return back()
                ->withInput()
                ->with('error', 'Total bobot Milestone + Nilai Anggota harus = 100%');
        }

        // Update bobot utama
        Setting::set('bobot_milestone', $request->bobot_milestone);
        Setting::set('bobot_nilai_anggota', $request->bobot_nilai_anggota);
        Setting::set('minimum_milestone', $request->minimum_milestone);
        Setting::set('bonus_per_minggu', $request->bonus_per_minggu);
        Setting::set('penalty_per_minggu', $request->penalty_per_minggu);

        // Update IT Project component weights
        $itFields = ['it_aktivitas_partisipatif', 'it_presentasi', 'it_objektivitas', 
                     'it_laporan_progres', 'it_laporan_akhir', 'it_produk_aplikasi'];
        foreach ($itFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->$field);
            }
        }

        // Update PWL component weights
        $pwlFields = ['pwl_proposal', 'pwl_progress_report', 'pwl_final_project', 
                      'pwl_presentasi', 'pwl_dokumentasi'];
        foreach ($pwlFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->$field);
            }
        }

        // Update Integrasi Sistem component weights
        $integrasiFields = ['integrasi_nilai_kerja', 'integrasi_nilai_laporan', 
                            'integrasi_ujian_praktikum_1', 'integrasi_ujian_praktikum_2',
                            'integrasi_uts', 'integrasi_uas'];
        foreach ($integrasiFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->$field);
            }
        }

        // Update TPK component weights
        $tpkFields = ['tpk_uts', 'tpk_uas', 'tpk_aktivitas_partisipatif',
                      'tpk_nilai_kerja', 'tpk_penyajian_dokumentasi', 'tpk_hasil_proyek'];
        foreach ($tpkFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->$field);
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
