<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'nilai';

    /**
     * Kolom yang bisa diisi secara massal
     */
    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'dosen_id',
        'laporan',
        'presentasi',
        'kontribusi',
        'uts',
        'uas',
        'hasil_proyek',
        // Integrasi Sistem
        'nilai_kerja',
        'nilai_laporan',
        'ujian_praktikum_1',
        'ujian_praktikum_2',
        // PWL (Pemrograman Web Lanjut)
        'pwl_tugas_quiz',
        'pwl_project',
        'pwl_presentasi',
        // IT Project
        'it_proposal',
        'it_progress_report',
        'it_final_project',
        'it_presentasi',
        'it_dokumentasi',
        'catatan',
    ];

    /**
     * Accessor untuk nilai huruf otomatis berdasarkan hasil_akhir
     */
    /**
     * Accessor untuk nilai akhir (numeric)
     */
    public function getNilaiAkhirAttribute()
    {
        $nilaiAkhir = $this->laporan; // Default

        if ($this->mataKuliah) {
            $namaMK = $this->mataKuliah->nama_mk;

            if (stripos($namaMK, 'pengambilan keputusan') !== false) {
                $nilaiAkhir = ($this->uts * 0.1) + ($this->uas * 0.1) + ($this->presentasi * 0.1) + ($this->kontribusi * 0.2) + ($this->laporan * 0.2) + ($this->hasil_proyek * 0.3);
            } elseif ($namaMK == 'Integrasi Sistem') {
                $aktivitas = (($this->nilai_kerja ?? 0) * 0.6) + (($this->nilai_laporan ?? 0) * 0.4);
                $project = (($this->ujian_praktikum_1 ?? 0) * 0.5) + (($this->ujian_praktikum_2 ?? 0) * 0.5);
                $nilaiAkhir = ($aktivitas * 0.45) + ($project * 0.25) + ($this->uts * 0.15) + ($this->uas * 0.15);
            } elseif (stripos($namaMK, 'pwl') !== false || stripos($namaMK, 'pemrograman web lanjut') !== false) {
                 // PWL (Assuming same as IT Project or default for now? Previous code didn't have specific PWL formula visible in replaced snippet but let's check `getNilaiHuruf` in file. 
                 // Wait, in the file `getNilaiHuruf` had PWL logic: "PWL menggunakan sistem nilai seperti IT Project" BUT check previous `Nilai.php` view.
                 // Actually, let's look at the previous `getNilaiHuruf` implementation in line 62.
                 // It used IT Project fields for PWL. I should replicate that.
                 // However, for IT Project, we JUST changed it to use `hasil_proyek` and `kontribusi` (or detailed components).
                 // LIMITATION: I need to be careful not to break PWL if I haven't updated PWL grading logic yet. 
                 // The user prompted about IT Project updates. I updated IT Project logic.
                 // If PWL uses the *Same* logic as IT Project *before* my update, it used `it_proposal` etc.
                 // If I updated IT Project logic to use `kontribusi` etc, PWL might need to check which fields are populated.
                 // Taking a safe approach: Just copy logic from `getNilaiHuruf` but return number.
                 
                 // Re-checking getNilaiHuruf logic for PWL from previous `view_file` (Step 77):
                 // elseif ... 'pwl' ... $nilaiAkhir = (($this->it_proposal ?? 0) * 0.15) ...
                 // My recent update (Step 131) ONLY touched IT Project block.
                 // So PWL still uses old IT Project columns? 
                 // Yes, likely. I should preserve that. 
                 
                 $nilaiAkhir = (($this->it_proposal ?? 0) * 0.15) + (($this->it_progress_report ?? 0) * 0.15) + (($this->it_final_project ?? 0) * 0.4) + (($this->it_presentasi ?? 0) * 0.2) + (($this->it_dokumentasi ?? 0) * 0.1);

            } elseif (stripos($namaMK, 'it project') !== false || stripos($namaMK, 'it proyek') !== false) {
                 // IT Project: Aktivitas 20%, Presentasi 10%, Objektivitas 10% (proposal), Lap Progres 10%, Lap Akhir 10% (dokumentasi), Produk 40% (final)
                $nilaiAkhir = (($this->kontribusi ?? 0) * 0.20) + 
                              (($this->it_presentasi ?? 0) * 0.10) + 
                              (($this->it_proposal ?? 0) * 0.10) + 
                              (($this->it_progress_report ?? 0) * 0.10) + 
                              (($this->it_dokumentasi ?? 0) * 0.10) + 
                              (($this->it_final_project ?? 0) * 0.40);
            }
        }
        
        return $nilaiAkhir;
    }

    /**
     * Accessor untuk nilai huruf otomatis berdasarkan hasil_akhir
     */
    public function getNilaiHurufAttribute()
    {
        $nilaiAkhir = $this->nilai_akhir;

        if ($nilaiAkhir >= 85) {
            return 'A';
        } elseif ($nilaiAkhir >= 75) {
            return 'B';
        } elseif ($nilaiAkhir >= 65) {
            return 'C';
        } elseif ($nilaiAkhir >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }

    /**
     * Accessor tambahan untuk format tanggal input
     */
    public function getTanggalInputAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M Y, H:i') : '-';
    }
    /**
     * Relasi ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke Mata Kuliah
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Relasi ke Dosen
     */
    /**
     * Relasi ke Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Accessor untuk mendapatkan nama dosen pengampu
     * Prioritas:
     * 1. Data dari relasi dosen() (dosen yang menginput nilai)
     * 2. Data dari relasi mataKuliah() (dosen coordinator/pengampu mata kuliah)
     */
    public function getNamaDosenPengampuAttribute()
    {
        // 1. Cek jika ada relasi dosen yang tersimpan di tabel nilai
        if ($this->dosen) {
            return $this->dosen->nama;
        }

        // 2. Jika tidak ada, cek dari Mata Kuliah
        if ($this->mataKuliah && $this->mataKuliah->nip_dosen) {
            // Ambil NIP pertama (asumsi koordinator atau pengampu utama)
            $nips = $this->mataKuliah->nip_dosen_array;
            if (!empty($nips)) {
                $nip = $nips[0];
                // Cari dosen berdasarkan NIP
                $dosen = \App\Models\Dosen::where('nip', $nip)->first();
                if ($dosen) {
                    return $dosen->nama;
                }
                
                // Fallback: Cari di tabel User jika data Dosen belum lengkap
                $userDosen = \App\Models\User::where('nim_nip', $nip)->first();
                if ($userDosen) {
                    return $userDosen->name;
                }
            }
        }

        return '-';
    }
}
