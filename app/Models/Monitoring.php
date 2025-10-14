<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $table = 'monitoring';
    protected $primaryKey = 'id_monitoring';
    protected $fillable = [
        'nama_kelompok',
        'judul_proyek',
        'status',
        'catatan',
        'tanggal_monitoring',
    ];
}
