<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';
    protected $primaryKey = 'id_kelompok';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'judul_proyek',
        'nip',
        'deskripsi',
    ];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'kelompok_id', 'id_kelompok');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'kelompok_id', 'id_kelompok');
    }
}
