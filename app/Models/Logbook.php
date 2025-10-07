<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'minggu_ke', // tambahin ini ya
        'judul',
        'kelompok',
        'rincian',
        'foto',
    ];
}
