<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks'; // nama tabel sesuai migration
    protected $primaryKey = 'id_kelompok'; // primary key sesuai migration

    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'deskripsi',
        'judul_proyek',
        'nip'
    ];
}
}
