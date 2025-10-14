<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_id',
        'user_id',
        'judul',
        'deskripsi',
        'minggu_ke',
        'status',
        'catatan_dosen',
    ];

    // Event untuk otomatis set user_id saat create
    protected static function booted()
    {
        static::creating(function ($milestone) {
            if (Auth::check() && empty($milestone->user_id)) {
                $milestone->user_id = Auth::id();
            }
        });
    }

    // Relasi ke Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id_kelompok');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
