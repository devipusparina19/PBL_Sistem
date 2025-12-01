<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// IMPORT semua model relasi
use App\Models\Mahasiswa;
use App\Models\NilaiKelompok;
use App\Models\Milestone;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok';
    protected $primaryKey = 'id_kelompok';

    protected $fillable = [
        'nama_kelompok',
        'judul_proyek',
        'dosen_id',
        'pemrograman_web',
        'integrasi_sistem',
        'pengambilan_keputusan',
        'it_proyek',
        'kontribusi_kelompok',
        'penilaian_dosen',
        'hasil_akhir'
    ];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id_kelompok');
    }

    public function nilaiKelompok()
    {
        return $this->hasOne(NilaiKelompok::class, 'kelompok_id', 'id_kelompok');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'kelompok_id', 'id_kelompok');
    }
}
