<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nim_nip',
        'kelas',
        'role',
        'role_kelompok',
        'role_di_kelompok',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Accessor untuk backward compatibility
    public function getNimAttribute()
    {
        return $this->nim_nip;
    }

    public function getNamaAttribute()
    {
        return $this->name;
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'user_id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim_nip');
    }
}
