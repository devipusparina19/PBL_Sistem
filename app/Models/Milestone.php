<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'milestones';

    // Kolom yang bisa diisi
    protected $fillable = [
        'judul',
        'deskripsi',
        'minggu_ke',
        'user_id',
        'kelompok_id',
        'status',
        'catatan_dosen',
    ];

    /**
     * Relasi ke User
     * Setiap milestone dimiliki oleh satu user (pembuat / pengunggah)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke Kelompok
     * Setiap milestone dimiliki oleh satu kelompok
     */
    public function kelompok()
    {
        // ✅ Fix: jelaskan secara eksplisit foreign key & primary key agar tidak cari kolom `id`
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id_kelompok');
    }

    /**
     * Scope untuk filter berdasarkan kelas
     * Berguna agar kelompok dengan nama sama di kelas berbeda tidak tercampur
     */
    public function scopeFilterByKelas($query, $kelas)
    {
        return $query->whereHas('kelompok', function ($q) use ($kelas) {
            $q->where('kelas', $kelas);
        });
    }
}
