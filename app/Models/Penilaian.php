<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian'; // atau 'penilaians' kalau pakai default plural
    protected $fillable = [
        'mahasiswa_id',
        'kelompok_id',
        'nilai',
        'keterangan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}
