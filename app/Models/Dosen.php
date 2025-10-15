<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens'; // pastikan ini sama dengan nama tabel di database

    protected $fillable = [
        'nama',
        'nip',
        'email',
        'no_telp',
        'kelas',
        'mata_kuliah',
    ];

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'dosen_id');
    }
}
