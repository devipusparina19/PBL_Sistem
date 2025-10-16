<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kelompok'; // ✅ Gunakan bentuk jamak sesuai konvensi Laravel

    // Primary key
    protected $primaryKey = 'id_kelompok';
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang boleh diisi
    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'judul_proyek',
        'kelas',
    ];

    // Jika tabel memiliki kolom created_at dan updated_at
    public $timestamps = true;

    // Relasi ke Milestone
    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'kelompok_id');
    }

    // Relasi opsional ke User (kalau nanti ada anggota/dosen pembimbing)
    public function users()
    {
        return $this->belongsToMany(User::class, 'kelompok_user', 'kelompok_id', 'user_id');
    }
}
