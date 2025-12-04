<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    // Nama tabel & primary key
    protected $table = 'kelompok';
    protected $primaryKey = 'id_kelompok';
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'judul_proyek',
        'kelas',
        'ketua_id',
    ];

    public $timestamps = true;

    /**
     * Relasi ke Milestone
     * Satu kelompok punya banyak milestone
     */
    public function milestones()
    {
        // milestones.kelompok_id -> kelompok.id_kelompok
        return $this->hasMany(Milestone::class, 'kelompok_id', 'id_kelompok');
    }

    /**
     * Accessor untuk memecah kode_mk jadi array (opsional)
     * Misal: "A01K,A02K" -> ['A01K', 'A02K']
     */
    public function getAnggotaAttribute()
    {
        if (!$this->kode_mk) {
            return [];
        }

        return array_map('trim', explode(',', $this->kode_mk));
    }

    /**
     * Accessor untuk menampilkan kode_mk sebagai string rapi
     */
    public function getAnggotaStringAttribute()
    {
        return implode(', ', $this->anggota);
    }

    /**
     * Relasi ke tabel Mahasiswa
     * Satu kelompok punya banyak mahasiswa (anggota)
     */
    public function mahasiswa()
    {
        // mahasiswas.kelompok_id -> kelompok.id_kelompok
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id_kelompok');
    }

    /**
     * Relasi ke Ketua Kelompok
     * ketua_id di tabel kelompok mengarah ke mahasiswas.id
     */
    public function ketua()
    {
        return $this->belongsTo(Mahasiswa::class, 'ketua_id', 'id');
    }
}
