<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi mass-assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_kelompok',      // ✅ tambahan
        'role_di_kelompok',   // ✅ tambahan
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting untuk kolom tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
