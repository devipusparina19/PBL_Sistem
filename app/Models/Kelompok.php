<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'kelompok';

    // Primary key
    protected $primaryKey = 'id_kelompok';
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi mass assignment
    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'judul_proyek',
        'kelas',
    ];

    // Aktifkan timestamps
    public $timestamps = true;

    // ===============================
    // 🔗 RELASI
    // ===============================

    // Relasi ke Milestone (satu kelompok punya banyak milestone)
    public function milestones()
    {
        // Pastikan foreign key di tabel milestone bernama 'id_kelompok'
        return $this->hasMany(Milestone::class, 'id_kelompok', 'id_kelompok');
    }

    // Relasi ke User (many-to-many lewat tabel pivot 'kelompok_user')
    public function users()
    {
        // Gunakan foreign key sesuai nama kolom di tabel pivot
        return $this->belongsToMany(
            User::class,
            'kelompok_user',    // nama tabel pivot
            'id_kelompok',      // foreign key di pivot yang mengarah ke tabel ini
            'id_user'           // foreign key di pivot yang mengarah ke tabel users
        );
    }
}
