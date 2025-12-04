<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'kelas',
        'angkatan',
        'email',
        'password',
        'foto',
        'kelompok_id', // ✅ kolom relasi ke tabel kelompok
    ];

    // 🔹 Relasi ke model Nilai
    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'mahasiswa_id');
    }

    // 🔹 Relasi ke model Kelompok
    public function kelompok()
    {
        // ✅ Pastikan tabel tujuannya "kelompok" (tanpa 's')
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id_kelompok');
    }
}
