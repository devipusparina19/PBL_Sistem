<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    // Nama tabel (Laravel otomatis paham, tapi tetap aman diset eksplisit)
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

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id_kelompok');
    }
}
