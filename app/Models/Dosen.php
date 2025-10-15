<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'email',
        'no_telepon',
        'kelas',
        'mata_kuliah',
    ];

    /**
     * Relasi ke Nilai (Dosen bisa memberi banyak nilai)
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'dosen_id');
    }
}
