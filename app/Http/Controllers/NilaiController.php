<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Menampilkan daftar nilai (khusus dosen atau admin)
     */
    public function index(Request $request)
    {
        $mahasiswas = Mahasiswa::orderBy('nama', 'asc')->get();
        
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
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        $mataKuliah = MataKuliah::orderBy('nama_mk', 'asc')->get();
        return view('nilai.create', compact('mahasiswa', 'mataKuliah'));
    }

    /**
     * Menyimpan nilai baru ke database
     */
    public function store(Request $request)
    {
        // Tambahan Debugging: Hapus baris ini setelah masalah teratasi
        // dd($request->all());

        $mataKuliah = MataKuliah::find($request->mata_kuliah_id);
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

        // 🔹 CATATAN: PWL menggunakan sistem nilai tunggal (form standar)
        // Tidak perlu kondisi khusus, langsung ke blok default

        // 🔹 Kasus khusus: IT Project
        } elseif ($mataKuliah && (stripos($mataKuliah->nama_mk, 'it project') !== false || stripos($mataKuliah->nama_mk, 'it proyek') !== false)) {
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
                'catatan' => 'Nilai Akhir IT Project: ' . round($nilaiAkhir, 2),
            ]);

        // 🔹 Kasus khusus: Pengambilan Keputusan
        } elseif ($mataKuliah && stripos($mataKuliah->nama_mk, 'pengambilan keputusan') !== false) {
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
     * Menampilkan form edit nilai
     */
    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        $mataKuliah = MataKuliah::orderBy('nama_mk', 'asc')->get();

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

        // 🔹 CATATAN: PWL menggunakan sistem nilai tunggal (form standar)
        // Tidak perlu kondisi khusus, langsung ke blok default

        // 🔹 Kasus khusus: IT Project
        } elseif ($mataKuliah && (stripos($mataKuliah->nama_mk, 'it project') !== false || stripos($mataKuliah->nama_mk, 'it proyek') !== false)) {
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
                'catatan' => 'Nilai Akhir IT Project: ' . round($nilaiAkhir, 2),
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
}
