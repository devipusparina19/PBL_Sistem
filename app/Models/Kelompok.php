<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';
    protected $primaryKey = 'id_kelompok'; // primary key sesuai migration
    public $incrementing = true;            // penting agar auto-increment
    protected $keyType = 'int';

    protected $fillable = [
        'kode_mk',
        'nama_kelompok',
        'judul_proyek',
        'nip',
        'deskripsi',
    ];
}