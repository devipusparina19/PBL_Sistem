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
        // Integrasi Sistem (NEW)
        'nilai_kerja',
        'nilai_laporan',
        'ujian_praktikum_1',
        'ujian_praktikum_2',
        'catatan',
    ];

    /**
     * Accessor untuk nilai huruf otomatis berdasarkan hasil_akhir
     */
    public function getNilaiHurufAttribute()
    {
        // Hitung nilai akhir berdasarkan mata kuliah
        $nilaiAkhir = $this->laporan; // Default
        if ($this->mataKuliah && stripos($this->mataKuliah->nama_mk, 'pengambilan keputusan') !== false) {
            $nilaiAkhir = ($this->uts * 0.1) + ($this->uas * 0.1) + ($this->presentasi * 0.1) + ($this->kontribusi * 0.2) + ($this->laporan * 0.2) + ($this->hasil_proyek * 0.3);
        } elseif ($this->mataKuliah && $this->mataKuliah->nama_mk == 'Integrasi Sistem') {
            $aktivitas = (($this->nilai_kerja ?? 0) * 0.6) + (($this->nilai_laporan ?? 0) * 0.4);
            $project = (($this->ujian_praktikum_1 ?? 0) * 0.5) + (($this->ujian_praktikum_2 ?? 0) * 0.5);
            $nilaiAkhir = ($aktivitas * 0.45) + ($project * 0.25) + ($this->uts * 0.15) + ($this->uas * 0.15);
        }

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
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
