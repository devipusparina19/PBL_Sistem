<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok';
    protected $primaryKey = 'id_kelompok';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'judul_proyek',
        'kelas',
    ];
    public $timestamps = true;

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'kelompok_id', 'id_kelompok');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'kelompok_user', 'kelompok_id', 'user_id');
    }

    public function getAnggotaAttribute()
    {
        if (!$this->kode_mk) {
            return [];
        }
        return array_map('trim', explode(',', $this->kode_mk));
    }

    public function getAnggotaStringAttribute()
    {
        return implode(', ', $this->anggota);
    }

    /**
     * 🔹 Relasi ke tabel Mahasiswa
     * Satu kelompok punya banyak mahasiswa
     */
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id_kelompok');
    }
}
