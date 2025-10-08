<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    /**
     * Nama tabel (kalau berbeda dari default 'nilais')
     * Kalau tabel kamu bernama 'nilai', tulis aja:
     */
    protected $table = 'nilai';

    /**
     * Field yang bisa diisi (mass assignable)
     */
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'laporan',
        'presentasi',
        'kontribusi',
        'total_nilai',
        'keterangan',
    ];

    /**
     * Relasi ke model Mahasiswa
     * Setiap nilai dimiliki oleh satu mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Relasi ke model Dosen (penilai)
     * Setiap nilai diberikan oleh satu dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Accessor untuk menampilkan nilai akhir dalam bentuk huruf (opsional)
     * Misalnya: A, B, C, D
     */
    public function getNilaiHurufAttribute()
    {
        $total = $this->total_nilai;

        if ($total >= 85) {
            return 'A';
        } elseif ($total >= 75) {
            return 'B';
        } elseif ($total >= 65) {
            return 'C';
        } elseif ($total >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }

    /**
     * Accessor opsional lain (contoh format tanggal)
     */
    public function getTanggalInputAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M Y, H:i') : '-';
    }
}
