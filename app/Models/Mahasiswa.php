<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'kelas',   
        'email',
        'password',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
