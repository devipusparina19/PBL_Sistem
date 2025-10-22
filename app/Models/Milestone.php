<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    // Nama tabel (opsional, tapi aman untuk eksplisit)
    protected $table = 'milestones';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'judul',
        'deskripsi',
        'minggu_ke',
        'user_id',
        'kelompok_id', // ✅ sudah sesuai dengan field hasil migrasi baru
        'status',
        'catatan_dosen',
    ];

    /**
     * Relasi ke User
     * Setiap milestone dimiliki oleh satu user (pembuat / pengunggah)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Kelompok
     * Setiap milestone dimiliki oleh satu kelompok
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id_kelompok');
        // 'kelompok_id' = kolom di tabel milestones
        // 'id_kelompok' = primary key di tabel kelompok
    }
}
