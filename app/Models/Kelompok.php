<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kelompok'; // ✅ gunakan nama tabel yang sesuai

    // Primary key
    protected $primaryKey = 'id_kelompok';
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang bisa diisi
    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'judul_proyek',
        'kelas',
    ];

    // Jika tabel punya created_at & updated_at
    public $timestamps = true;

    /**
     * Relasi ke model Milestone
     * Satu kelompok punya banyak milestone
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'kelompok_id', 'id_kelompok');
    }

    /**
     * Relasi ke model User (anggota kelompok)
     * Dipertahankan kalau nanti mau dipakai
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'kelompok_user', 'kelompok_id', 'user_id');
    }

    /**
     * 🔹 Accessor otomatis untuk ambil daftar anggota dari kolom kode_mk
     * Misal kode_mk = "A01K, A02K, A03K, A04K"
     * Akan dikonversi ke array: ["A01K", "A02K", "A03K", "A04K"]
     */
    public function getAnggotaAttribute()
    {
        if (!$this->kode_mk) {
            return [];
        }

        // Pisahkan berdasarkan koma dan hilangkan spasi ekstra
        return array_map('trim', explode(',', $this->kode_mk));
    }

    /**
     * 🔹 (Opsional) Dapatkan daftar anggota dalam bentuk teks siap tampil
     * Hasil: "A01K, A02K, A03K, A04K"
     */
    public function getAnggotaStringAttribute()
    {
        return implode(', ', $this->anggota);
    }
}
