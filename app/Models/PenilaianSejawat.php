<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianSejawat extends Model
{
    use HasFactory;

    protected $table = 'penilaian_sejawat';

    protected $fillable = [
        'penilai_id',
        'dinilai_id',
        'nilai',
    ];

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    public function dinilai()
    {
        return $this->belongsTo(User::class, 'dinilai_id');
    }
}
