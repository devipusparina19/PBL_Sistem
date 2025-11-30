<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Pastikan migration tabel kamu bernama 'dosens'
    protected $table = 'dosens';

    protected $fillable = [
        'nama',
        'nip',          // dari model pertama
        'nidn',         // dari model kedua
        'email',
        'no_telp',      // dari model pertama
        'no_hp',        // dari model kedua
        'kelas',
        'mata_kuliah',  // string (boleh daftar mata kuliah)
    ];

    /**
     * Relasi ke tabel nilai (model Nilai)
     * Dari model pertama
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'dosen_id');
    }

    /**
     * Relasi ke tabel kelompok
     * Dari model kedua → ditambahkan, tidak dihapus
     */
    public function kelompok()
    {
        return $this->hasMany(Kelompok::class);
    }

    /**
     * Getter untuk mata kuliah → mengubah string jadi array
     */
    public function getMataKuliahListAttribute()
    {
        if (empty($this->mata_kuliah)) {
            return [];
        }

        return preg_split('/[,;\n]+/', $this->mata_kuliah);
    }

    /**
     * Setter untuk mata kuliah → menyimpan array sebagai string dipisah koma
     */
    public function setMataKuliahListAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['mata_kuliah'] = implode(', ', array_filter($value));
        } else {
            $this->attributes['mata_kuliah'] = $value;
        }
    }
}
