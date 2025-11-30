<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // PAKSA Laravel memakai tabel 'mahasiswas'
    protected $table = 'mahasiswas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'nim',
        'kelas',
        'angkatan',
        'email',
        'password',
        'foto',
        'kelompok_id'
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id_kelompok');
    }
}
