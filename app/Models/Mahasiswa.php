<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'kelas',
        'angkatan',
        'email',
        'password',
    ];
    
     public function nilai()
    {
        return $this->hasOne(Nilai::class, 'mahasiswa_id');
        // atau hasMany kalau bisa punya banyak nilai
    }
}
