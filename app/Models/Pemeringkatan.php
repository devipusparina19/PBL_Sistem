<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeringkatan extends Model
{
    protected $table = 'pemeringkatan';

    protected $fillable = [
        'kelompok_id',
        'nilai_total',
        'peringkat'
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id_kelompok');
    }
}
