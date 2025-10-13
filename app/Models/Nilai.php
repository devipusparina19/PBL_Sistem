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
        'pemrograman_web',
        'integrasi_sistem',
        'pengambilan_keputusan',
        'it_proyek',
        'kontribusi_kelompok',
        'penilaian_sejawat',
        'hasil_akhir',
    ];

    /**
     * Accessor untuk nilai huruf otomatis berdasarkan hasil_akhir
     */
    public function getNilaiHurufAttribute()
    {
        $nilai = $this->hasil_akhir;

        if ($nilai >= 85) {
            return 'A';
        } elseif ($nilai >= 75) {
            return 'B';
        } elseif ($nilai >= 65) {
            return 'C';
        } elseif ($nilai >= 50) {
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
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
