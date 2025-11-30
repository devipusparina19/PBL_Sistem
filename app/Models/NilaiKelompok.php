<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiKelompok extends Model
{
    protected $fillable = [
        'kelompok_id',
        'presentasi',
        'laporan',
        'kerjasama',
        'nilai_akhir'
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }
}
