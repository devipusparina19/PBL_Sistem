<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Menampilkan daftar nilai (khusus dosen atau admin)
     */
    public function index(Request $request)
    {
        if (Auth::user()->role === 'dosen') {
            $nip = Auth::user()->nim_nip;
            // Ambil mata kuliah yang diampu dosen
            $mataKuliahDosen = MataKuliah::all()->filter(function($mk) use ($nip) {
                return in_array($nip, $mk->nip_dosen_array);
            });
            
            // Ambil daftar kelas dari mata kuliah tersebut
            $kelasDosen = $mataKuliahDosen->pluck('kelas')->unique();
            
            // Ambil mahasiswa yang berada di kelas tersebut
            $mahasiswas = Mahasiswa::whereIn('kelas', $kelasDosen)->orderBy('nama', 'asc')->get();
        } else {
            $mahasiswas = Mahasiswa::orderBy('nama', 'asc')->get();
        }
        
        // Ambil mahasiswa_id dari request (filter)
        $selectedMahasiswaId = $request->get('mahasiswa_id');
        $selectedMahasiswa = null;
        $nilai = collect();

        if ($selectedMahasiswaId) {
            // Jika ada mahasiswa yang dipilih, ambil datanya
            $selectedMahasiswa = Mahasiswa::find($selectedMahasiswaId);
            
            // Ambil nilai mahasiswa yang dipilih
            $nilai = Nilai::with(['mahasiswa', 'mataKuliah', 'dosen'])
                        ->where('mahasiswa_id', $selectedMahasiswaId)
                        ->orderBy('created_at', 'desc')
                        ->get();
        } elseif (Auth::user()->role === 'mahasiswa') {
            // Jika mahasiswa login, tampilkan nilainya langsung
            $selectedMahasiswa = Auth::user()->mahasiswa;
            if ($selectedMahasiswa) {
                $nilai = Nilai::with(['mahasiswa', 'mataKuliah', 'dosen'])
                            ->where('mahasiswa_id', $selectedMahasiswa->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
            }
        }

        return view('nilai.index', compact('mahasiswas', 'selectedMahasiswa', 'nilai'));
    }

    /**
     * Menampilkan form tambah nilai baru
     */
    public function create()
    {
        if (Auth::user()->role === 'dosen') {
            $nip = Auth::user()->nim_nip;
            // Filter mata kuliah sesuai NIP dosen
            $mataKuliah = MataKuliah::all()->filter(function($mk) use ($nip) {
                return in_array($nip, $mk->nip_dosen_array);
            });
            
            // Ambil daftar kelas dari mata kuliah tersebut
            $kelasDosen = $mataKuliah->pluck('kelas')->unique();
            
            // Filter mahasiswa sesuai kelas yang diampu
            $mahasiswa = Mahasiswa::whereIn('kelas', $kelasDosen)->orderBy('nama', 'asc')->get();
        } else {
            $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
            $mataKuliah = MataKuliah::orderBy('nama_mk', 'asc')->get();
        }
        
        // Get current weight settings for inline editing
        $settings = Setting::all()->keyBy('key');
        
        return view('nilai.create', compact('mahasiswa', 'mataKuliah', 'settings'));
    }

    /**
     * Menyimpan nilai baru ke database
     */
    public function store(Request $request)
    {
        $mataKuliah = MataKuliah::find($request->mata_kuliah_id);
        
        // Tambahan Debugging: Hapus baris ini setelah masalah teratasi
        // \Log::info('=== DEBUG NILAI STORE ===');
        // \Log::info('Mata Kuliah ID: ' . $request->mata_kuliah_id);
        // \Log::info('Mata Kuliah Nama: ' . ($mataKuliah ? $mataKuliah->nama_mk : 'NULL'));
        // \Log::info('Request Data:', $request->all());
        // dd([
        //     'mata_kuliah_id' => $request->mata_kuliah_id,
        //     'mata_kuliah_nama' => $mataKuliah ? $mataKuliah->nama_mk : 'NULL',
        //     'request_data' => $request->all()
        // ]);

        $dosenId = null;
        if (Auth::user()->role === 'dosen') {
            $dosen = \App\Models\Dosen::where('email', Auth::user()->email)->first();
            $dosenId = $dosen ? $dosen->id : null;
        }

        // 🔹 Kasus khusus: Integrasi Sistem
       if ($mataKuliah && $mataKuliah->nama_mk == 'Integrasi Sistem') {
    $request->validate([
        'mahasiswa_id' => 'required|exists:mahasiswas,id',
        'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
        'nilai_kerja' => 'required|numeric|min:0|max:100',
        'nilai_laporan' => 'required|numeric|min:0|max:100',
        'ujian_praktikum_1' => 'required|numeric|min:0|max:100',
        'ujian_praktikum_2' => 'required|numeric|min:0|max:100',
        'uts' => 'required|numeric|min:0|max:100',
        'uas' => 'required|numeric|min:0|max:100',
    ]);

    // Get weights from form (inline editing) or fallback to settings
    $wNilaiKerja = (float) ($request->w_integrasi_nilai_kerja ?? Setting::get('integrasi_nilai_kerja', 27)) / 100;
    $wNilaiLaporan = (float) ($request->w_integrasi_nilai_laporan ?? Setting::get('integrasi_nilai_laporan', 18)) / 100;
    $wUP1 = (float) ($request->w_integrasi_up1 ?? Setting::get('integrasi_ujian_praktikum_1', 12.5)) / 100;
    $wUP2 = (float) ($request->w_integrasi_up2 ?? Setting::get('integrasi_ujian_praktikum_2', 12.5)) / 100;
    $wUTS = (float) ($request->w_integrasi_uts ?? Setting::get('integrasi_uts', 15)) / 100;
    $wUAS = (float) ($request->w_integrasi_uas ?? Setting::get('integrasi_uas', 15)) / 100;
    
    // Save weights to settings if changed
    if ($request->has('w_integrasi_nilai_kerja')) {
        Setting::set('integrasi_nilai_kerja', $request->w_integrasi_nilai_kerja);
        Setting::set('integrasi_nilai_laporan', $request->w_integrasi_nilai_laporan);
        Setting::set('integrasi_ujian_praktikum_1', $request->w_integrasi_up1);
        Setting::set('integrasi_ujian_praktikum_2', $request->w_integrasi_up2);
        Setting::set('integrasi_uts', $request->w_integrasi_uts);
        Setting::set('integrasi_uas', $request->w_integrasi_uas);
    }
    
    $nilai_akhir = ($request->nilai_kerja * $wNilaiKerja) +
                   ($request->nilai_laporan * $wNilaiLaporan) +
                   ($request->ujian_praktikum_1 * $wUP1) +
                   ($request->ujian_praktikum_2 * $wUP2) +
                   ($request->uts * $wUTS) +
                   ($request->uas * $wUAS);

    Nilai::create([
        'mahasiswa_id' => $request->mahasiswa_id,
        'mata_kuliah_id' => $request->mata_kuliah_id,
        'dosen_id' => $dosenId,
        'nilai_kerja' => $request->nilai_kerja,
        'nilai_laporan' => $request->nilai_laporan,
        'laporan' => $request->nilai_laporan,
        'presentasi' => 0,  // Tambahkan baris ini
        'kontribusi' => 0,  // Tambahkan baris ini
        'ujian_praktikum_1' => $request->ujian_praktikum_1,
        'ujian_praktikum_2' => $request->ujian_praktikum_2,
        'uts' => $request->uts,
        'uas' => $request->uas,
        'hasil_proyek' => round($nilai_akhir, 2),
        'catatan' => 'Nilai Akhir: ' . round($nilai_akhir, 2),
    ]);

        // 🔹 Kasus khusus: PWL (Pemrograman Web Lanjut)
        } elseif ($mataKuliah && (stripos($mataKuliah->nama_mk, 'pwl') !== false || stripos($mataKuliah->nama_mk, 'pemrograman web') !== false || stripos($mataKuliah->nama_mk, 'perograman web') !== false)) {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'it_proposal' => 'required|numeric|min:0|max:100',
                'it_progress_report' => 'required|numeric|min:0|max:100',
                'it_final_project' => 'required|numeric|min:0|max:100',
                'it_presentasi' => 'required|numeric|min:0|max:100',
                'it_dokumentasi' => 'required|numeric|min:0|max:100',
            ]);

            // Get weights from form (inline editing) or fallback to settings
            $wProposal = (float) ($request->w_pwl_proposal ?? Setting::get('pwl_proposal', 15)) / 100;
            $wProgressReport = (float) ($request->w_pwl_progress_report ?? Setting::get('pwl_progress_report', 15)) / 100;
            $wFinalProject = (float) ($request->w_pwl_final_project ?? Setting::get('pwl_final_project', 40)) / 100;
            $wPresentasi = (float) ($request->w_pwl_presentasi ?? Setting::get('pwl_presentasi', 20)) / 100;
            $wDokumentasi = (float) ($request->w_pwl_dokumentasi ?? Setting::get('pwl_dokumentasi', 10)) / 100;
            
            // Save weights to settings if changed
            if ($request->has('w_pwl_proposal')) {
                Setting::set('pwl_proposal', $request->w_pwl_proposal);
                Setting::set('pwl_progress_report', $request->w_pwl_progress_report);
                Setting::set('pwl_final_project', $request->w_pwl_final_project);
                Setting::set('pwl_presentasi', $request->w_pwl_presentasi);
                Setting::set('pwl_dokumentasi', $request->w_pwl_dokumentasi);
            }
            
            $nilaiAkhir = ($request->it_proposal * $wProposal) + 
                          ($request->it_progress_report * $wProgressReport) + 
                          ($request->it_final_project * $wFinalProject) + 
                          ($request->it_presentasi * $wPresentasi) + 
                          ($request->it_dokumentasi * $wDokumentasi);

            Nilai::create([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'it_proposal' => $request->it_proposal,
                'it_progress_report' => $request->it_progress_report,
                'it_final_project' => $request->it_final_project,
                'it_presentasi' => $request->it_presentasi,
                'it_dokumentasi' => $request->it_dokumentasi,
                'laporan' => 0,
                'presentasi' => 0,
                'kontribusi' => 0,
                'hasil_proyek' => round($nilaiAkhir, 2),
                'catatan' => 'Nilai Akhir PWL: ' . round($nilaiAkhir, 2),
            ]);

        // 🔹 Kasus khusus: IT Project
        } elseif ($mataKuliah && (stripos($mataKuliah->nama_mk, 'it project') !== false || stripos($mataKuliah->nama_mk, 'it proyek') !== false)) {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'kontribusi' => 'required|numeric|min:0|max:100', // Aktivitas Partisipatif (20%)
                'it_presentasi' => 'required|numeric|min:0|max:100', // Presentasi (10%)
                'it_proposal' => 'required|numeric|min:0|max:100', // Repurpose for Objektivitas (10%)
                'it_progress_report' => 'required|numeric|min:0|max:100', // Laporan Progres (10%)
                'it_dokumentasi' => 'required|numeric|min:0|max:100', // Laporan Akhir (10%)
                'it_final_project' => 'required|numeric|min:0|max:100', // Produk Aplikasi (40%)
            ]);

            // Get weights from form (inline editing) or fallback to settings
            $wAktivitas = (float) ($request->w_it_aktivitas ?? Setting::get('it_aktivitas_partisipatif', 20)) / 100;
            $wPresentasi = (float) ($request->w_it_presentasi ?? Setting::get('it_presentasi', 10)) / 100;
            $wObjektivitas = (float) ($request->w_it_objektivitas ?? Setting::get('it_objektivitas', 10)) / 100;
            $wLapProgres = (float) ($request->w_it_laporan_progres ?? Setting::get('it_laporan_progres', 10)) / 100;
            $wLapAkhir = (float) ($request->w_it_laporan_akhir ?? Setting::get('it_laporan_akhir', 10)) / 100;
            $wProduk = (float) ($request->w_it_produk ?? Setting::get('it_produk_aplikasi', 40)) / 100;
            
            // Save weights to settings if changed
            if ($request->has('w_it_aktivitas')) {
                Setting::set('it_aktivitas_partisipatif', $request->w_it_aktivitas);
                Setting::set('it_presentasi', $request->w_it_presentasi);
                Setting::set('it_objektivitas', $request->w_it_objektivitas);
                Setting::set('it_laporan_progres', $request->w_it_laporan_progres);
                Setting::set('it_laporan_akhir', $request->w_it_laporan_akhir);
                Setting::set('it_produk_aplikasi', $request->w_it_produk);
            }
            
            $nilaiAkhir = ($request->kontribusi * $wAktivitas) + 
                          ($request->it_presentasi * $wPresentasi) +
                          ($request->it_proposal * $wObjektivitas) +
                          ($request->it_progress_report * $wLapProgres) +
                          ($request->it_dokumentasi * $wLapAkhir) +
                          ($request->it_final_project * $wProduk);

            Nilai::create([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'kontribusi' => $request->kontribusi, // Aktivitas Partisipatif
                'it_presentasi' => $request->it_presentasi, // Presentasi
                'it_proposal' => $request->it_proposal, // Objektivitas
                'it_progress_report' => $request->it_progress_report, // Laporan Progres
                'it_dokumentasi' => $request->it_dokumentasi, // Laporan Akhir
                'it_final_project' => $request->it_final_project, // Produk Aplikasi
                'hasil_proyek' => round($nilaiAkhir, 2), // Simpan Total Nilai di sini juga untuk referensi cepat
                'laporan' => 0,
                'presentasi' => 0,
                'catatan' => 'Nilai Akhir IT Project: ' . round($nilaiAkhir, 2),
            ]);

        // 🔹 Kasus khusus: Pengambilan Keputusan
        } elseif ($mataKuliah && (stripos($mataKuliah->nama_mk, 'pengambilan keputusan') !== false || stripos($mataKuliah->nama_mk, 'teknik pengambilan') !== false)) {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'uts' => 'required|numeric|min:0|max:100',
                'uas' => 'required|numeric|min:0|max:100',
                'aktivitas_partisipatif' => 'required|numeric|min:0|max:100',
                'nilai_kerja' => 'required|numeric|min:0|max:100',
                'penyajian_dokumentasi' => 'required|numeric|min:0|max:100',
                'hasil_proyek' => 'required|numeric|min:0|max:100',
            ]);

            // Get weights from form (inline editing) or fallback to settings
            $wUTS = (float) ($request->w_tpk_uts ?? Setting::get('tpk_uts', 10)) / 100;
            $wUAS = (float) ($request->w_tpk_uas ?? Setting::get('tpk_uas', 10)) / 100;
            $wKeaktifan = (float) ($request->w_tpk_keaktifan ?? Setting::get('tpk_aktivitas_partisipatif', 10)) / 100;
            $wNilaiKerja = (float) ($request->w_tpk_nilai_kerja ?? Setting::get('tpk_nilai_kerja', 20)) / 100;
            $wPenyajian = (float) ($request->w_tpk_penyajian ?? Setting::get('tpk_penyajian_dokumentasi', 20)) / 100;
            $wHasilProyek = (float) ($request->w_tpk_hasil_proyek ?? Setting::get('tpk_hasil_proyek', 30)) / 100;
            
            // Save weights to settings if changed
            if ($request->has('w_tpk_uts')) {
                Setting::set('tpk_uts', $request->w_tpk_uts);
                Setting::set('tpk_uas', $request->w_tpk_uas);
                Setting::set('tpk_aktivitas_partisipatif', $request->w_tpk_keaktifan);
                Setting::set('tpk_nilai_kerja', $request->w_tpk_nilai_kerja);
                Setting::set('tpk_penyajian_dokumentasi', $request->w_tpk_penyajian);
                Setting::set('tpk_hasil_proyek', $request->w_tpk_hasil_proyek);
            }
            
            $nilaiAkhir = ($request->uts * $wUTS) + 
                          ($request->uas * $wUAS) + 
                          ($request->aktivitas_partisipatif * $wKeaktifan) + 
                          ($request->nilai_kerja * $wNilaiKerja) + 
                          ($request->penyajian_dokumentasi * $wPenyajian) + 
                          ($request->hasil_proyek * $wHasilProyek);

            Nilai::create([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'uts' => $request->uts,
                'uas' => $request->uas,
                'presentasi' => $request->aktivitas_partisipatif,
                'kontribusi' => $request->nilai_kerja,
                'laporan' => $request->penyajian_dokumentasi,
                'hasil_proyek' => $request->hasil_proyek,
                'catatan' => 'Nilai Akhir: ' . round($nilaiAkhir, 2),
            ]);

        // 🔹 Default: mata kuliah standar
        } else {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'nilai' => 'required|numeric|min:0|max:100',
            ]);

            Nilai::create([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'laporan' => $request->nilai,
                'presentasi' => 0,
                'kontribusi' => 0,
                'hasil_proyek' => 0,
            ]);
        }

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan!');
    }

    /**
     * CATCH ALL ERROR
     */
    private function handleStoreError($e, $request) {
        \Log::error('ERROR STORE NILAI: ' . $e->getMessage());
        \Log::error('Stack Trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
    }

    /**
     * Menampilkan form edit nilai
     */
    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        
        if (Auth::user()->role === 'dosen') {
            $nip = Auth::user()->nim_nip;
            // Filter mata kuliah sesuai NIP dosen
            $mataKuliah = MataKuliah::all()->filter(function($mk) use ($nip) {
                return in_array($nip, $mk->nip_dosen_array);
            });
            
            // Ambil daftar kelas dari mata kuliah tersebut
            $kelasDosen = $mataKuliah->pluck('kelas')->unique();
            
            // Filter mahasiswa sesuai kelas yang diampu
            $mahasiswa = Mahasiswa::whereIn('kelas', $kelasDosen)->orderBy('nama', 'asc')->get();
        } else {
            $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
            $mataKuliah = MataKuliah::orderBy('nama_mk', 'asc')->get();
        }

        return view('nilai.edit', compact('nilai', 'mahasiswa', 'mataKuliah'));
    }

    /**
     * Mengupdate data nilai
     */
    public function update(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        // Cek apakah mahasiswa sudah punya nilai untuk mata kuliah ini (kecuali record yang sedang diedit)
        $existing = Nilai::where('mahasiswa_id', $request->mahasiswa_id)
                        ->where('mata_kuliah_id', $request->mata_kuliah_id)
                        ->where('id', '!=', $id)
                        ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['mata_kuliah_id' => 'Mahasiswa ini sudah memiliki nilai untuk mata kuliah yang dipilih.']);
        }

        // Ambil data mata kuliah untuk menentukan jenis penilaian
        $mataKuliah = MataKuliah::find($request->mata_kuliah_id);

        // Dapatkan dosen_id dari user yang login (matching by email)
        $dosenId = null;
        if (Auth::user()->role === 'dosen') {
            $dosen = \App\Models\Dosen::where('email', Auth::user()->email)->first();
            $dosenId = $dosen ? $dosen->id : null;
        }

        // 🔹 Kasus khusus: Integrasi Sistem
        if ($mataKuliah && $mataKuliah->nama_mk == 'Integrasi Sistem') {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'nilai_kerja' => 'required|numeric|min:0|max:100',
                'nilai_laporan' => 'required|numeric|min:0|max:100',
                'ujian_praktikum_1' => 'required|numeric|min:0|max:100',
                'ujian_praktikum_2' => 'required|numeric|min:0|max:100',
                'uts' => 'required|numeric|min:0|max:100',
                'uas' => 'required|numeric|min:0|max:100',
            ]);

            $aktivitas_partisipatif = ($request->nilai_kerja * 0.6) + ($request->nilai_laporan * 0.4);
            $hasil_project = ($request->ujian_praktikum_1 * 0.5) + ($request->ujian_praktikum_2 * 0.5);
            $nilai_akhir = ($aktivitas_partisipatif * 0.45) +
                           ($hasil_project * 0.25) +
                           ($request->uts * 0.15) +
                           ($request->uas * 0.15);

            $nilai->update([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'nilai_kerja' => $request->nilai_kerja,
                'nilai_laporan' => $request->nilai_laporan,
                'laporan' => $request->nilai_laporan,
                'presentasi' => 0,
                'kontribusi' => 0,
                'ujian_praktikum_1' => $request->ujian_praktikum_1,
                'ujian_praktikum_2' => $request->ujian_praktikum_2,
                'uts' => $request->uts,
                'uas' => $request->uas,
                'hasil_proyek' => round($nilai_akhir, 2),
                'catatan' => 'Nilai Akhir: ' . round($nilai_akhir, 2),
            ]);

        // 🔹 Kasus khusus: PWL (Pemrograman Web Lanjut)
        } elseif ($mataKuliah && (stripos($mataKuliah->nama_mk, 'pwl') !== false || stripos($mataKuliah->nama_mk, 'pemrograman web') !== false || stripos($mataKuliah->nama_mk, 'perograman web') !== false)) {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'it_proposal' => 'required|numeric|min:0|max:100',
                'it_progress_report' => 'required|numeric|min:0|max:100',
                'it_final_project' => 'required|numeric|min:0|max:100',
                'it_presentasi' => 'required|numeric|min:0|max:100',
                'it_dokumentasi' => 'required|numeric|min:0|max:100',
            ]);

            $nilaiAkhir = ($request->it_proposal * 0.15) + 
                          ($request->it_progress_report * 0.15) + 
                          ($request->it_final_project * 0.4) + 
                          ($request->it_presentasi * 0.2) + 
                          ($request->it_dokumentasi * 0.1);

            $nilai->update([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'it_proposal' => $request->it_proposal,
                'it_progress_report' => $request->it_progress_report,
                'it_final_project' => $request->it_final_project,
                'it_presentasi' => $request->it_presentasi,
                'it_dokumentasi' => $request->it_dokumentasi,
                'laporan' => 0,
                'presentasi' => 0,
                'kontribusi' => 0,
                'hasil_proyek' => round($nilaiAkhir, 2),
                'catatan' => 'Nilai Akhir PWL: ' . round($nilaiAkhir, 2),
            ]);

        // 🔹 Kasus khusus: IT Project
        } elseif ($mataKuliah && (stripos($mataKuliah->nama_mk, 'it project') !== false || stripos($mataKuliah->nama_mk, 'it proyek') !== false)) {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'kontribusi' => 'required|numeric|min:0|max:100', // Aktivitas Partisipatif (20%)
                'it_presentasi' => 'required|numeric|min:0|max:100', // Presentasi (10%)
                'it_proposal' => 'required|numeric|min:0|max:100', // Objektivitas (10%)
                'it_progress_report' => 'required|numeric|min:0|max:100', // Laporan Progres (10%)
                'it_dokumentasi' => 'required|numeric|min:0|max:100', // Laporan Akhir (10%)
                'it_final_project' => 'required|numeric|min:0|max:100', // Produk Aplikasi (40%)
            ]);

            $nilaiAkhir = ($request->kontribusi * 0.20) + 
                          ($request->it_presentasi * 0.10) +
                          ($request->it_proposal * 0.10) +
                          ($request->it_progress_report * 0.10) +
                          ($request->it_dokumentasi * 0.10) +
                          ($request->it_final_project * 0.40);

            $nilai->update([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'kontribusi' => $request->kontribusi,
                'it_presentasi' => $request->it_presentasi,
                'it_proposal' => $request->it_proposal,
                'it_progress_report' => $request->it_progress_report,
                'it_dokumentasi' => $request->it_dokumentasi,
                'it_final_project' => $request->it_final_project,
                'hasil_proyek' => round($nilaiAkhir, 2),
                'laporan' => 0,
                'presentasi' => 0,
                'catatan' => 'Nilai Akhir IT Project: ' . round($nilaiAkhir, 2),
            ]);

        // 🔹 Kasus khusus: Pengambilan Keputusan
        } elseif ($mataKuliah && (stripos($mataKuliah->nama_mk, 'pengambilan keputusan') !== false || stripos($mataKuliah->nama_mk, 'teknik pengambilan') !== false)) {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'uts' => 'required|numeric|min:0|max:100',
                'uas' => 'required|numeric|min:0|max:100',
                'aktivitas_partisipatif' => 'required|numeric|min:0|max:100',
                'nilai_kerja' => 'required|numeric|min:0|max:100',
                'penyajian_dokumentasi' => 'required|numeric|min:0|max:100',
                'hasil_proyek' => 'required|numeric|min:0|max:100',
            ]);

            $nilaiAkhir = ($request->uts * 0.1) + 
                          ($request->uas * 0.1) + 
                          ($request->aktivitas_partisipatif * 0.1) + 
                          ($request->nilai_kerja * 0.2) + 
                          ($request->penyajian_dokumentasi * 0.2) + 
                          ($request->hasil_proyek * 0.3);

            $nilai->update([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'uts' => $request->uts,
                'uas' => $request->uas,
                'presentasi' => $request->aktivitas_partisipatif,
                'kontribusi' => $request->nilai_kerja,
                'laporan' => $request->penyajian_dokumentasi,
                'hasil_proyek' => $request->hasil_proyek,
                'catatan' => 'Nilai Akhir: ' . round($nilaiAkhir, 2),
            ]);

        // 🔹 Default: mata kuliah standar
        } else {
            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
                'nilai' => 'required|numeric|min:0|max:100',
            ]);

            $nilai->update([
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $dosenId,
                'laporan' => $request->nilai,
                'presentasi' => 0,
                'kontribusi' => 0,
            ]);
        }

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui!');
    }

    /**
     * Menghapus data nilai
     */
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Data nilai berhasil dihapus!');
    }

    /**
     * Halaman pemilihan mata kuliah oleh dosen
     */
    public function pilihMatkul()
    {
        $mataKuliah = \App\Models\MataKuliah::all(); // ubah variabel jadi $mataKuliah
        return view('dosen.pilihMatkul', compact('mataKuliah')); // compact sesuai variabel
    }

    /**
     * Form input nilai untuk mata kuliah tertentu (old method - deprecated)
     */
    public function createForMatkul($matkul_id)
    {
        $matkul = MataKuliah::findOrFail($matkul_id);
        $mahasiswa = Mahasiswa::all();
        return view('dosen.input-nilai', compact('matkul', 'mahasiswa'));
    }

    /**
     * Store nilai untuk mata kuliah tertentu (old method - deprecated)
     */
    public function storeForMatkul(Request $request, $matkul_id)
    {
        $request->validate([
            'mahasiswa_id' => 'required',
            'laporan' => 'required|numeric|min:0|max:100',
            'presentasi' => 'required|numeric|min:0|max:100',
            'kontribusi' => 'required|numeric|min:0|max:100',
        ]);

        Nilai::updateOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
                'mata_kuliah_id' => $matkul_id
            ],
            [
                'laporan' => $request->laporan,
                'presentasi' => $request->presentasi,
                'kontribusi' => $request->kontribusi,
                'catatan' => $request->catatan,
            ]
        );

        return redirect()->route('nilai.createForMatkul', $matkul_id)
            ->with('success', 'Nilai berhasil disimpan!');
    }

    public function inputNilai($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $mahasiswa = Mahasiswa::where('mata_kuliah_id', $id)->get();

        return view('dosen.input_nilai', compact('mataKuliah', 'mahasiswa'));
    }

    /**
     * Export nilai mahasiswa to Excel (CSV format)
     */
    public function exportExcel()
    {
        $user = Auth::user();
        
        // Get mahasiswa data
        $mahasiswa = Mahasiswa::where('nim', $user->nim_nip)->first();
        
        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        $nilai = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->with('mataKuliah')
            ->get();
        
        $filename = 'nilai_' . $mahasiswa->nim . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($nilai, $mahasiswa) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Header info
            fputcsv($file, ['REKAP NILAI MAHASISWA']);
            fputcsv($file, ['Nama', $mahasiswa->nama]);
            fputcsv($file, ['NIM', $mahasiswa->nim]);
            fputcsv($file, ['Kelas', $mahasiswa->kelas ?? '-']);
            fputcsv($file, ['Tanggal Export', date('d/m/Y H:i:s')]);
            fputcsv($file, []); // Empty row
            
            // Column headers
            fputcsv($file, ['No', 'Mata Kuliah', 'Nilai Akhir', 'Grade']);
            
            // Data
            $no = 1;
            foreach ($nilai as $n) {
                $nilaiAkhir = $this->calculateNilaiAkhir($n);
                $grade = $this->getGrade($nilaiAkhir);
                
                fputcsv($file, [
                    $no++,
                    $n->mataKuliah->nama_mk ?? '-',
                    number_format($nilaiAkhir, 2),
                    $grade
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export nilai mahasiswa to PDF
     */
    public function exportPdf()
    {
        $user = Auth::user();
        
        // Get mahasiswa data
        $mahasiswa = Mahasiswa::where('nim', $user->nim_nip)->first();
        
        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        $nilai = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->with('mataKuliah')
            ->get();
        
        // Generate HTML for PDF
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Nilai ' . $mahasiswa->nama . '</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
            h1 { text-align: center; color: #333; font-size: 18px; }
            .info { margin-bottom: 20px; }
            .info p { margin: 5px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
            th { background-color: #0d6efd; color: white; }
            tr:nth-child(even) { background-color: #f8f9fa; }
            .grade-A { color: #198754; font-weight: bold; }
            .grade-B { color: #0d6efd; font-weight: bold; }
            .grade-C { color: #ffc107; font-weight: bold; }
            .grade-D { color: #dc3545; font-weight: bold; }
            .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #666; }
        </style>
        </head><body>
        <h1>REKAP NILAI MAHASISWA PBL</h1>
        <div class="info">
            <p><strong>Nama:</strong> ' . $mahasiswa->nama . '</p>
            <p><strong>NIM:</strong> ' . $mahasiswa->nim . '</p>
            <p><strong>Kelas:</strong> ' . ($mahasiswa->kelas ?? '-') . '</p>
            <p><strong>Program Studi:</strong> Teknologi Informasi</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Mata Kuliah</th>
                    <th style="width: 100px;">Nilai Akhir</th>
                    <th style="width: 80px;">Grade</th>
                </tr>
            </thead>
            <tbody>';
        
        $no = 1;
        foreach ($nilai as $n) {
            $nilaiAkhir = $this->calculateNilaiAkhir($n);
            $grade = $this->getGrade($nilaiAkhir);
            $gradeClass = 'grade-' . substr($grade, 0, 1);
            
            $html .= '<tr>
                <td>' . $no++ . '</td>
                <td style="text-align: left;">' . ($n->mataKuliah->nama_mk ?? '-') . '</td>
                <td>' . number_format($nilaiAkhir, 2) . '</td>
                <td class="' . $gradeClass . '">' . $grade . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>
        <div class="footer">Dicetak pada: ' . date('d/m/Y H:i:s') . '</div>
        </body></html>';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="nilai_' . $mahasiswa->nim . '_' . date('Y-m-d') . '.html"');
    }

    /**
     * Calculate nilai akhir based on mata kuliah type
     */
    private function calculateNilaiAkhir($n)
    {
        $nilaiAkhir = $n->laporan ?? 0;

        // Pengambilan Keputusan
        if($n->mataKuliah && (stripos($n->mataKuliah->nama_mk, 'pengambilan keputusan') !== false || stripos($n->mataKuliah->nama_mk, 'teknik pengambilan') !== false)) {
            $nilaiAkhir = (($n->uts ?? 0) * 0.1) + (($n->uas ?? 0) * 0.1) + 
                          (($n->presentasi ?? 0) * 0.1) + (($n->kontribusi ?? 0) * 0.2) +
                          (($n->laporan ?? 0) * 0.2) + (($n->hasil_proyek ?? 0) * 0.3);
        }
        // Integrasi Sistem
        elseif($n->mataKuliah && $n->mataKuliah->nama_mk == 'Integrasi Sistem') {
            $aktivitas = (($n->nilai_kerja ?? 0) * 0.6) + (($n->nilai_laporan ?? 0) * 0.4);
            $project = (($n->ujian_praktikum_1 ?? 0) * 0.5) + (($n->ujian_praktikum_2 ?? 0) * 0.5);
            $nilaiAkhir = ($aktivitas * 0.45) + ($project * 0.25) + 
                          (($n->uts ?? 0) * 0.15) + (($n->uas ?? 0) * 0.15);
        }
        // PWL
        elseif($n->mataKuliah && (stripos($n->mataKuliah->nama_mk, 'pwl') !== false || stripos($n->mataKuliah->nama_mk, 'pemrograman web') !== false)) {
            $nilaiAkhir = (($n->it_proposal ?? 0) * 0.15) + 
                          (($n->it_progress_report ?? 0) * 0.15) + 
                          (($n->it_final_project ?? 0) * 0.4) + 
                          (($n->it_presentasi ?? 0) * 0.2) + 
                          (($n->it_dokumentasi ?? 0) * 0.1);
        }
        // IT Project
        elseif($n->mataKuliah && (stripos($n->mataKuliah->nama_mk, 'it project') !== false || stripos($n->mataKuliah->nama_mk, 'it proyek') !== false)) {
            $nilaiAkhir = (($n->it_proposal ?? 0) * 0.15) + 
                          (($n->it_progress_report ?? 0) * 0.15) + 
                          (($n->it_final_project ?? 0) * 0.4) + 
                          (($n->it_presentasi ?? 0) * 0.2) + 
                          (($n->it_dokumentasi ?? 0) * 0.1);
        }
        
        return $nilaiAkhir;
    }

    /**
     * Get grade letter from nilai
     */
    private function getGrade($nilai)
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 75) return 'B+';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 50) return 'D';
        return 'E';
    }
}
