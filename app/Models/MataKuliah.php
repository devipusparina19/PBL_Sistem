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
        'nip_dosen',
        'kelas',
        'semester',
    ];

    /**
     * Relasi ke dosen (jika ingin menghubungkan dengan tabel dosens)
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip_dosen', 'nip');
    }
}
