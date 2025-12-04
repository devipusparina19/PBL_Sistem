<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    // Nama tabel sesuai di database
    protected $table = 'mata_kuliah';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'kelas',
        'nip_dosen',
    ];

    /**
     * Relasi ke dosen (jika ingin menghubungkan dengan tabel dosens)
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip_dosen', 'nip');
    }

    /**
     * Accessor untuk mendapatkan array NIP dosen
     */
    public function getNipDosenArrayAttribute()
    {
        if (empty($this->nip_dosen)) {
            return [];
        }
        // Pisahkan berdasarkan koma dan trim spasi
        return array_map('trim', explode(',', $this->nip_dosen));
    }

    /**
     * Accessor untuk mendapatkan nama-nama dosen
     * Return: array of dosen names
     */
    public function getDosenNamesAttribute()
    {
        $nips = $this->nip_dosen_array;
        
        if (empty($nips)) {
            return [];
        }

        // Ambil nama dosen berdasarkan NIP
        $dosens = Dosen::whereIn('nip', $nips)->get();
        
        return $dosens->pluck('nama')->toArray();
    }

    /**
     * Accessor untuk mendapatkan nama dosen dalam format string
     * Return: "Nama Dosen 1, Nama Dosen 2"
     */
    public function getDosenNamesStringAttribute()
    {
        $names = $this->dosen_names;
        
        if (empty($names)) {
            return '-';
        }

        return implode(', ', $names);
    }

    /**
     * Accessor untuk mendapatkan data dosen lengkap (NIP + Nama)
     * Return: array of objects with 'nip' and 'nama'
     */
    public function getDosensAttribute()
    {
        $nips = $this->nip_dosen_array;
        
        if (empty($nips)) {
            return collect();
        }

        return Dosen::whereIn('nip', $nips)->get();
    }
}
